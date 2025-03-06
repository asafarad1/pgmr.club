<?php

return function ($site, $kirby) {


    if ($kirby->request()->is("POST")) {
        $action = get("action");
        $workshop = page(get("workshop_id"));
        if ($action && $workshop) {
            if ($action == "register") {
                if ($workshop->getAvailability() && !$workshop->participants()->toUsers()->has($kirby->user())) {
                    $participants = $workshop->participants()->toUsers()->add($kirby->user())->toArray();
                    try {
                        $updated_workshop = $workshop->update([
                            "participants" => $participants
                        ]);
                    } catch (Exception $e) {
                    }
                    go("/");
                }
            }
        } elseif ($action == "unregister") {
            if ($workshop->participants()->toUsers()->has($kirby->user())) {
                $participants = $workshop->participants()->toUsers()->remove($kirby->user()->id())->toArray();
                try {
                    $updated_workshop = $workshop->update([
                        "participants" => $participants
                    ]);
                } catch (Exception $e) {
                }
                go("/");
            }
        }
    }

    $workshops = $site->getWorkshops()->sortBy("title", "asc");

    return compact("workshops");
};
