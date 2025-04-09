<?php

// This file should be placed in site/controllers/ directory

return function($kirby, $site, $pages, $page) {
    $alert = null;
    $response = [];
    
    // Handle AJAX requests
    if($kirby->request()->is('POST') && $kirby->request()->ajax()) {
        $action = get('action');
        $workshopId = get('workshop_id');
        $workshop = page($workshopId);
        
        // Process registration/unregistration
        if($action === 'register') {
            if($kirby->user() && $workshop && $workshop->getAvailability()) {
                try {
                    $participants = $workshop->participants()->toUsers();
                    $participants->add($kirby->user());
                    
                    $workshop->update([
                        'participants' => implode(', ', $participants->id())
                    ]);
                    
                    $response['status'] = 'success';
                    $response['message'] = 'Successfully registered';
                } catch(Exception $e) {
                    $response['status'] = 'error';
                    $response['message'] = 'Registration failed: ' . $e->getMessage();
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Workshop not available';
            }
        } elseif($action === 'unregister') {
            if($kirby->user() && $workshop) {
                try {
                    $participants = $workshop->participants()->toUsers();
                    $participants->remove($kirby->user());
                    
                    $workshop->update([
                        'participants' => implode(', ', $participants->id())
                    ]);
                    
                    $response['status'] = 'success';
                    $response['message'] = 'Successfully unregistered';
                } catch(Exception $e) {
                    $response['status'] = 'error';
                    $response['message'] = 'Unregistration failed: ' . $e->getMessage();
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Workshop not found';
            }
        }
        
        // Return JSON response for AJAX requests
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    // Continue with regular page processing for non-AJAX requests
    // (Your existing controller code for regular page loads would go here)
    
    return [
        'alert' => $alert,
    ];
};
