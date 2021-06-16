<?php

namespace App\DTOs\Reports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ReportScoreDTO {

    const ACCESIBILITY_TYPE = "accessibility";
    const PERFORMANCE_TYPE = "performance";
    const CATEGORIES_TYPE = "categories";
    const SCORE = "score";

    private Collection $accessibility;
    private Collection $performance;
    private Collection $categories;
    private Collection $scores;
    private $reportId;

    public function __construct(?Array $scores, ?int $reportId)
    {
        $this->reportId = $reportId;
        $this->scores = collect($scores);
        $this->categories = $this->scores->has('categories') ? collect($this->scores->get('categories')) : collect();
        $this->accessibility = $this->categories->has('accessibility') ? collect($this->categories->get('accessibility')) : collect();
        $this->performance = $this->categories->has('performance') ? collect($this->categories->get('performance')) : collect();
    }

    public function getAccessibility()
    {
        return $this->accessibility;
    }

    public function getPerformance()
    {
        return $this->performance;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function getReportId()
    {
        return $this->reportId;
    }

}