<?php

namespace Controller;

/**
 * Activity stream
 *
 * @package controller
 * @author  Frederic Guillot
 */
class Activity extends Base
{
    /**
     * Activity page for a project
     *
     * @access public
     */
    public function project()
    {
        $project = $this->getProject();

        $this->response->html($this->template->layout('activity/project', array(
            'board_selector' => $this->projectPermission->getAllowedProjects($this->userSession->getId()),
            'events' => $this->projectActivity->getProject($project['id']),
            'project' => $project,
            'title' => t('%s\'s activity', $project['name'])
        )));
    }
}