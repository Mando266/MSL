<?php

namespace App\Models\Containers;

use App\Models\Booking\Booking;
use App\Models\Master\Agents;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Ports;
use App\Models\Master\Vessels;
use App\Models\Voyages\Voyages;
use App\Traits\HasFilter;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Movements extends Model implements PermissionSeederContract
{
    use HasFilter;

    protected $table = 'movements';
    protected $guarded = [];
    public static int $lessorId = 0;

    protected $fillable = [
        'container_id',
        'container_type_id',
        'movement_id',
        'movement_date',
        'port_location_id',
        'pol_id',
        'pod_id',
        'vessel_id',
        'voyage_id',
        'terminal_id',
        'booking_no',
        'bl_no',
        'remarkes',
        'transshipment_port_id',
        'booking_agent_id',
        'free_time',
        'container_status',
        'import_agent',
        'free_time_origin',
    ];

    use PermissionSeederTrait;


//    protected static function booted()
//    {
//        if (!app()->runningInConsole() && !request()->is('api/*')) {
//            if (auth()->user()) {
//                $lessor_id = (int)auth()->user()->lessor_id;
//
//                if ($lessor_id != 0) {
//                    session()->put('lessorId', $lessor_id);
//                }
//            }
//            $lessor_id = session('lessorId') ?? 0;
//            if ($lessor_id != 0) {
//                static::addGlobalScope('lessor', function (Builder $builder) use ($lessor_id) {
//                    $builder->whereHas('container', function ($q) use ($lessor_id) {
//                        $q->where('description', $lessor_id);
//                    });
//                });
//            }
//        }
//    }

    public function getPermissionActions()
    {
        return config('permission_seeder.actions', [
            'List',
            'Create',
            'Edit',
            'Delete'
        ]);
    }

    public function pol()
    {
        return $this->belongsTo(Ports::class, 'pol_id', 'id');
    }

    public function pod()
    {
        return $this->belongsTo(Ports::class, 'pod_id', 'id');
    }

    public function activitylocation()
    {
        return $this->belongsTo(Ports::class, 'port_location_id', 'id');
    }

    public function movementcode()
    {
        return $this->belongsTo(ContainersMovement::class, 'movement_id', 'id');
    }

    public function container()
    {
        return $this->belongsTo(Containers::class, 'container_id', 'id');
    }

    public function containersType()
    {
        return $this->belongsTo(ContainersTypes::class, 'container_type_id', 'id');
    }

    public function vessels()
    {
        return $this->belongsTo(Vessels::class, 'vessel_id', 'id');
    }

    public function voyage()
    {
        return $this->belongsTo(Voyages::class, 'voyage_id', 'id');
    }

    public function bookingAgent()
    {
        return $this->belongsTo(Agents::class, 'booking_agent_id', 'id');
    }

    public function importAgent()
    {
        return $this->belongsTo(Agents::class, 'import_agent', 'id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_no');
    }

}
