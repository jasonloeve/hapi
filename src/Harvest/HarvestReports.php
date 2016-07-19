<?php


namespace Harvest;

use Harvest\Model\Range;
use Harvest\Model\Result;
use Harvest\Model\Task;

/**
 * HarvestReports
 *
 * This file contains the class HarvestReports
 *
 */

/**
 * HarvestReports defines some aggregative reporting methods for quickly
 * obtaining information on users, projects, and weekly statuses
 *
 * <code>
 * // require the Harvest API core class
 * require_once( PATH_TO_LIB . '/HarvestReports.php' );
 *
 * // register the class auto loader
 * spl_autoload_register( array('HarvestReports', 'autoload') );
 *
 * // instantiate the api object
 * $api = new HarvestReports();
 * $api->setUser( "user@email.com" );
 * $api->setPassword( "password" );
 * $api->setAccount( "account" );
 * </code>
 *
 */
class HarvestReports extends HarvestApi
{
    /**
     * @var string Start of Week
     */
    protected $_startOfWeek = 0;

    /**
     * @var string Time Zone
     */
    protected $_timeZone = null;

    /**
     * set Start of Work Week for use in Entry Reports
     *
     * <code>
     * $api = new HarvestReports();
     * $api->setStartOfWeek( HarvestReports::MONDAY );
     * </code>
     *
     * @param  string $startOfWeek Start day of work week
     * @return void
     */
    public function setStartOfWeek($startOfWeek)
    {
        $this->_startOfWeek = $startOfWeek;
    }

    /**
     * set TimeZone for use in Entry Reports
     *
     * <code>
     * $api = new HarvestReports();
     * $api->setTimeZone( "EST" );
     * </code>
     *
     * @param  string $timeZone User Time Zone
     * @return void
     */
    public function setTimeZone($timeZone)
    {
        $this->_timeZone = $timeZone;
    }

    /**
     * get all active clients
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getActiveClients();
     * if ( $result->isSuccess() ) {
     *     $clients = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getActiveClients()
    {
        $result = $this->getClients();
        if ( $result->isSuccess() ) {
            $clients = array();
            foreach ($result->data as $client) {
                if ($client->active == "true") {
                    $clients[$client->id] = $client;
                }
            }
            $result->data = $clients;
        }

        return $result;
    }

    /**
     * get all inactive clients
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getInactiveClients();
     * if ( $result->isSuccess() ) {
     *     $clients = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getInactiveClients()
    {
        $result = $this->getClients();
        if ( $result->isSuccess() ) {
            $clients = array();
            foreach ($result->data as $client) {
                if ($client->active == "false") {
                    $clients[$client->id] = $client;
                }
            }
            $result->data = $clients;
        }

        return $result;
    }

    /**
     * get all active projects
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getActiveProjects();
     * if ( $result->isSuccess() ) {
     *     $projects = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getActiveProjects()
    {
        $result = $this->getProjects();
        if ( $result->isSuccess() ) {
            $projects = array();
            foreach ($result->data as $project) {
                if ($project->active == "true") {
                    $projects[$project->id] = $project;
                }
            }
            $result->data = $projects;
        }

        return $result;
    }

    /**
     * get all inactive projects
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getInactiveProjects();
     * if ( $result->isSuccess() ) {
     *     $projects = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getInactiveProjects()
    {
        $result = $this->getProjects();
        if ( $result->isSuccess() ) {
            $projects = array();
            foreach ($result->data as $project) {
                if ($project->active == "false") {
                    $projects[$project->id] = $project;
                }
            }
            $result->data = $projects;
        }

        return $result;
    }

    /**
     * get all active projects
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getClientActiveProjects( 12345 );
     * if ( $result->isSuccess() ) {
     *     $projects = $result->data;
     * }
     * </code>
     *
     * @param  int    $client_id Client Identifier
     * @return Result
     */
    public function getClientActiveProjects($client_id)
    {
        $result = $this->getClientProjects( $client_id );
        if ( $result->isSuccess() ) {
            $projects = array();
            foreach ($result->data as $project) {
                if ($project->active == "true") {
                    $projects[$project->id] = $project;
                }
            }
            $result->data = $projects;
        }

        return $result;
    }

    /**
     * get all inactive projects of a Client
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getClientInactiveProjects();
     * if ( $result->isSuccess() ) {
     *     $projects = $result->data;
     * }
     * </code>
     *
     * @param  int    $client_id Client Identifier
     * @return Result
     */
    public function getClientInactiveProjects($client_id)
    {
        $result = $this->getClientProjects( $client_id );
        if ( $result->isSuccess() ) {
            $projects = array();
            foreach ($result->data as $project) {
                if ($project->active == "false") {
                    $projects[$project->id] = $project;
                }
            }
            $result->data = $projects;
        }

        return $result;
    }

    /**
     * get all active users
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getActiveUsers();
     * if ( $result->isSuccess() ) {
     *     $users = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getActiveUsers()
    {
        $result = $this->getUsers();
        if ( $result->isSuccess() ) {
            $data = array();
            foreach ($result->data as $obj) {
                /** @var \Harvest\Model\User $obj */
                if ( $obj->get("is-active") == "true" ) {
                    $data[$obj->id] = $obj;
                }
            }
            $result->data = $data;
        }

        return $result;
    }

    /**
     * get all inactive users
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getInactiveUsers();
     * if ( $result->isSuccess() ) {
     *     $users = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getInactiveUsers()
    {
        $result = $this->getUsers();
        if ( $result->isSuccess() ) {
            $data = array();
            foreach ($result->data as $obj) {
                /** @var \Harvest\Model\User $obj */
                if ( $obj->get("is-active") == "false" ) {
                    $data[$obj->id] = $obj;
                }
            }
            $result->data = $data;
        }

        return $result;
    }

    /**
     * get all admin users
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getAdmins();
     * if ( $result->isSuccess() ) {
     *     $users = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getAdmins()
    {
        $result = $this->getUsers();
        if ( $result->isSuccess() ) {
            $data = array();
            foreach ($result->data as $obj) {
                /** @var \Harvest\Model\User $obj */
                if ( $obj->get("is-admin") == "true" ) {
                    $data[$obj->id] = $obj;
                }
            }
            $result->data = $data;
        }

        return $result;
    }

    /**
     * get all active admin users
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getActiveAdmins();
     * if ( $result->isSuccess() ) {
     *     $user = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getActiveAdmins()
    {
        $result = $this->getUsers();
        if ( $result->isSuccess() ) {
            $data = array();
            foreach ($result->data as $obj) {
                /** @var \Harvest\Model\User $obj */
                if ( $obj->get("is-active") == "true" && $obj->get("is-admin") == "true" ) {
                    $data[$obj->id] = $obj;
                }
            }
            $result->data = $data;
        }

        return $result;
    }

    /**
     * get all inactive admin users
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getInactiveAdmins();
     * if ( $result->isSuccess() ) {
     *     $users = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getInactiveAdmins()
    {
        $result = $this->getUsers();
        if ( $result->isSuccess() ) {
            $data = array();
            foreach ($result->data as $obj) {
                /** @var \Harvest\Model\User $obj */
                if ( $obj->get("is-active") == "false" && $obj->get("is-admin") ) {
                    $data[$obj->id] = $obj;
                }
            }
            $result->data = $data;
        }

        return $result;
    }

    /**
     * get all contractor users
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getContractors();
     * if ( $result->isSuccess() ) {
     *     $users = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getContractors()
    {
        $result = $this->getUsers();
        if ( $result->isSuccess() ) {
            $data = array();
            foreach ($result->data as $obj) {
                /** @var \Harvest\Model\User $obj */
                if ( $obj->get("is-contractor") == "true" ) {
                    $data[$obj->id] = $obj;
                }
            }
            $result->data = $data;
        }

        return $result;
    }

    /**
     * get all active contractor users
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getActiveContractors();
     * if ( $result->isSuccess() ) {
     *     $user = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getActiveContractors()
    {
        $result = $this->getUsers();
        if ( $result->isSuccess() ) {
            $data = array();
            foreach ($result->data as $obj) {
                /** @var \Harvest\Model\User $obj */
                if ( $obj->get("is-active") == "true" && $obj->get("is-contractor") == "true" ) {
                    $data[$obj->id] = $obj;
                }
            }
            $result->data = $data;
        }

        return $result;
    }

    /**
     * get all inactive contractor users
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getInactiveContractors();
     * if ( $result->isSuccess() ) {
     *     $users = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getInactiveContractors()
    {
        $result = $this->getUsers();
        if ( $result->isSuccess() ) {
            $data = array();
            foreach ($result->data as $obj) {
                /** @var \Harvest\Model\User $obj */
                if ( $obj->get("is-active") == "false" && $obj->get("is-contractor") ) {
                    $data[$obj->id] = $obj;
                }
            }
            $result->data = $data;
        }

        return $result;
    }

    /**
     * get all active time entries
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getActiveTimers( );
     * if ( $result->isSuccess() ) {
     *     $entries = $result->data;
     * }
     * </code>
     *
     * @return Result
     */
    public function getActiveTimers()
    {
        $result = $this->getActiveUsers( );
        if ( $result->isSuccess() ) {
            $data = array();
            foreach ($result->data as $user) {
                $subResult = $this->getUserEntries( $user->id, Range::today( $this->_timeZone ) );
                if ( $subResult->isSuccess() ) {
                    foreach ($subResult->data as $entry) {
                        if ($entry->timer_started_at != null || $entry->timer_started_at != "") {
                            $data[$user->id] = $entry;
                            break;
                        }
                    }
                }
            }
            $result->data = $data;
        }

        return $result;
    }

    /**
     * get a user's active time entry
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getUsersActiveTimer( 12345 );
     * if ( $result->isSuccess() ) {
     *     $activeTimer = $result->data;
     * }
     * </code>
     *
     * @param $user_id
     * @return Result
     */
    public function getUsersActiveTimer($user_id)
    {
        $result = $this->getUserEntries( $user_id, Range::today( $this->_timeZone ) );
        if ( $result->isSuccess() ) {
            $data = null;
            foreach ($result->data as $entry) {
                if ($entry->timer_started_at != null || $entry->timer_started_at != "") {
                    $data = $entry;
                    break;
                }
            }
            $result->data = $data;
        }

        return $result;
    }

    /**
     * get all tasks assigned to a project
     *
     * <code>
     * $api = new HarvestReports();
     *
     * $result = $api->getProjectTasks( 12345 );
     * if ( $result->isSuccess() ) {
     *     $tasks = $result->data;
     * }
     * </code>
     *
     * @param $project_id
     * @return Result
     */
    public function getProjectTasks($project_id)
    {
        $result = $this->getProjectTaskAssignments($project_id);
        if ($result->isSuccess()) {
            $tasks = array();
            foreach ($result->data as $taskAssignment) {
                $taskResult = $this->getTask($taskAssignment->task_id);
                if ($taskResult->isSuccess()) {
                    $tasks[$taskResult->data->id] = $taskResult->data;
                }
            }
            $result->data = $tasks;
        }

        return $result;
    }

}
