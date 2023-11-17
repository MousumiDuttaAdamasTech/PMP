<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_year_id',
        'vertical_id',
        'budget_type',
        'period_start',
        'period_end',
        'period_name',
        'value',
        'unit',
        'parent_id',
    ];

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function vertical()
    {
        return $this->belongsTo(Vertical::class, 'vertical_id');
    }

    public function parentBudget()
    {
        return $this->belongsTo(RevenueBudget::class, 'parent_id');
    }

    public function childBudgets()
    {
        return $this->hasMany(RevenueBudget::class, 'parent_id');
    }

    // Custom method to validate period dates
    public function validateDates()
    {
        $financialYear = $this->financialYear;
        return $this->period_start >= $financialYear->start_date && $this->period_end <= $financialYear->end_date;
    }

    // Custom method to check value summation for children
    public function validateValueSummation()
    {
        if ($this->childBudgets->count() > 0) {
            return $this->childBudgets->sum('value') === $this->value;
        }
        return true;
    }

    // Custom method to check unit consistency with parent
    public function validateUnitConsistency()
    {
        if ($this->parentBudget) {
            return $this->unit === $this->parentBudget->unit;
        }
        return true;
    }

    public function isCompanyWide()
    {
        return is_null($this->vertical_id);
    }
}
