<?php

class WorkshopPage extends \Kirby\Cms\Page {

    public function getLimit() {
        if ( $limit = $this->content()->limit()->toInt() ) {
            return $limit;
        }
        return site()->content()->limit()->toInt();
    }

    public function getAvailability() {
        return $this->getLimit() - $this->content()->participants()->toUsers()->count();
    }

    public function getFeedbackItems() {
        return $this->children()->filterBy( "intendedTemplate", "feedback-item" );
    }

}