<?php

namespace App\Traits;

use App\ContactPerson;

trait HasContactPeople
{
    public function contactPeople()
    {
        return $this->hasMany(ContactPerson::class, 'customer_id');
    }

    public function storeContactPeople($data): void
    {
        $contactPeople = collect();
        foreach ($data['role'] ?? [] as $k => $v) {
            $item = array_map(fn($values) => $values[$k] ?? null, $data);
            $contactPeople->push($item);
        }
        
        $this->contactPeople()->delete();

        foreach ($contactPeople->filter() as $person) {
            if (array_filter($person, fn($s) => $s !== null)) {
                $person['customer_id'] = $this->id;
                ContactPerson::create($person);
            }
        }
    }

    public static function allWithContactEmails(\Closure $callable = null)
    {
        $query = static::query();
        if ($callable) {
            $callable($query);
        }
        $customers = $query->with('contactPeople')->get();
        return $customers->map(fn($s) => collect([
            'emails' => $s->contactPeople->pluck('email')->push(str_replace(' - ', "\r\n", $s->email))->implode("\r\n"),
            'name' => $s->name
        ]));
    }
}