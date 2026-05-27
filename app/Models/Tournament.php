<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Tournament extends Model
{
    protected $fillable = [
        'name', 'description', 'status', 'user_id', 'is_public',
        'format', 'groups_count', 'advance_per_group',
    ];

    public function teams()
    {
        return $this->hasMany(TournamentTeam::class)->orderBy('seed');
    }

    public function matches()
    {
        return $this->hasMany(TournamentMatch::class)->orderBy('round')->orderBy('position');
    }

    public function groups()
    {
        return $this->hasMany(TournamentGroup::class)->orderBy('name');
    }

    public function groupMatches()
    {
        return $this->hasMany(TournamentGroupMatch::class);
    }

    // ── Single elimination ────────────────────────────────────────────────────

    public function generateBracket(?Collection $teamList = null): void
    {
        $teams = ($teamList ?? $this->teams()->get())->shuffle()->values();
        $teamCount  = $teams->count();
        $totalRounds = (int) ceil(log(max($teamCount, 2), 2));
        $totalSlots  = (int) pow(2, $totalRounds);

        $slots = $teams->toArray();
        while (count($slots) < $totalSlots) {
            $slots[] = null;
        }

        $r1Matches = $totalSlots / 2;
        for ($i = 0; $i < $r1Matches; $i++) {
            $t1 = $slots[$i * 2]     ?? null;
            $t2 = $slots[$i * 2 + 1] ?? null;

            $match = TournamentMatch::create([
                'tournament_id' => $this->id,
                'round'         => 1,
                'position'      => $i,
                'team1_id'      => $t1['id'] ?? null,
                'team2_id'      => $t2['id'] ?? null,
            ]);

            if ($t1 && !$t2) $match->update(['winner_id' => $t1['id']]);
            elseif (!$t1 && $t2) $match->update(['winner_id' => $t2['id']]);
        }

        for ($round = 2; $round <= $totalRounds; $round++) {
            $count = (int) pow(2, $totalRounds - $round);
            for ($i = 0; $i < $count; $i++) {
                TournamentMatch::create([
                    'tournament_id' => $this->id,
                    'round'         => $round,
                    'position'      => $i,
                ]);
            }
        }

        foreach ($this->matches()->where('round', 1)->get() as $m) {
            if ($m->winner_id) $this->advanceWinner($m);
        }

        $this->update(['status' => 'active']);
    }

    public function advanceWinner(TournamentMatch $match): void
    {
        $nextMatch = $this->matches()
            ->where('round',    $match->round + 1)
            ->where('position', (int) floor($match->position / 2))
            ->first();

        if (!$nextMatch) return;

        $match->position % 2 === 0
            ? $nextMatch->update(['team1_id' => $match->winner_id])
            : $nextMatch->update(['team2_id' => $match->winner_id]);

        $nextMatch->refresh();

        if ($nextMatch->team1_id && !$nextMatch->team2_id) {
            $nextMatch->update(['winner_id' => $nextMatch->team1_id]);
            $this->advanceWinner($nextMatch);
        } elseif (!$nextMatch->team1_id && $nextMatch->team2_id) {
            $nextMatch->update(['winner_id' => $nextMatch->team2_id]);
            $this->advanceWinner($nextMatch);
        }
    }

    // ── Group stage ───────────────────────────────────────────────────────────

    public function generateGroupStage(): void
    {
        $teams      = $this->teams()->get()->shuffle()->values();
        $groupCount = max(2, (int) $this->groups_count);
        $letters    = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        $groups = [];
        for ($i = 0; $i < $groupCount; $i++) {
            $groups[] = TournamentGroup::create([
                'tournament_id' => $this->id,
                'name'          => $letters[$i],
            ]);
        }

        foreach ($teams as $i => $team) {
            TournamentGroupTeam::create([
                'group_id' => $groups[$i % $groupCount]->id,
                'team_id'  => $team->id,
            ]);
        }

        foreach ($groups as $group) {
            $ids = TournamentGroupTeam::where('group_id', $group->id)->pluck('team_id')->toArray();
            $n   = count($ids);
            for ($i = 0; $i < $n; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    TournamentGroupMatch::create([
                        'tournament_id' => $this->id,
                        'group_id'      => $group->id,
                        'team1_id'      => $ids[$i],
                        'team2_id'      => $ids[$j],
                    ]);
                }
            }
        }

        $this->update(['status' => 'active']);
    }

    public function groupStageComplete(): bool
    {
        return $this->groupMatches()->whereNull('winner_id')->count() === 0
            && $this->groupMatches()->count() > 0;
    }

    public function generateKnockoutFromGroups(): void
    {
        $advance  = max(1, (int) $this->advance_per_group);
        $advancing = collect();

        foreach ($this->groups()->get() as $group) {
            $group->load(['groupTeams.team', 'matches']);
            $top = $group->standings()->take($advance)->pluck('team');
            $advancing = $advancing->concat($top);
        }

        $this->matches()->delete();
        $this->generateBracket($advancing);
    }
}
