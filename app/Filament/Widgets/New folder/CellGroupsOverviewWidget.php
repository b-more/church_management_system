<?php

namespace App\Filament\Widgets;

use App\Models\CellGroup;
use App\Models\Member;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class CellGroupsOverviewWidget extends Widget
{
    protected static string $view = 'filament.widgets.cell-groups-overview';

    protected int | string | array $columnSpan = 1;

    protected static ?string $pollingInterval = '60s';

    public function getCellGroupsData(): array
    {
        $cellGroups = CellGroup::with(['members' => function($query) {
                $query->where('is_active', true);
            }])
            ->where('status', 'Active')
            ->get()
            ->map(function ($group) {
                $memberCount = $group->members->count();
                $leaderName = $group->leader_id ?
                    Member::find($group->leader_id)?->first_name . ' ' . Member::find($group->leader_id)?->last_name :
                    'No Leader';

                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'leader' => $leaderName,
                    'member_count' => $memberCount,
                    'meeting_day' => $group->meeting_day,
                    'meeting_time' => $group->meeting_time?->format('H:i'),
                    'location' => $group->meeting_location,
                    'health_status' => $this->getGroupHealthStatus($memberCount),
                ];
            });

        $stats = [
            'total_groups' => $cellGroups->count(),
            'total_members' => $cellGroups->sum('member_count'),
            'avg_group_size' => $cellGroups->avg('member_count'),
            'groups_needing_attention' => $cellGroups->where('health_status.color', 'danger')->count(),
        ];

        return [
            'cell_groups' => $cellGroups,
            'stats' => $stats,
        ];
    }

    private function getGroupHealthStatus(int $memberCount): array
    {
        if ($memberCount >= 12) {
            return ['status' => 'Ready to Multiply', 'color' => 'success'];
        } elseif ($memberCount >= 8) {
            return ['status' => 'Growing Well', 'color' => 'success'];
        } elseif ($memberCount >= 5) {
            return ['status' => 'Stable', 'color' => 'warning'];
        } elseif ($memberCount >= 3) {
            return ['status' => 'Needs Growth', 'color' => 'danger'];
        } else {
            return ['status' => 'Critical', 'color' => 'danger'];
        }
    }
}
