<?php
    /**
     * Associative array containing the minimum user level required for specific actions.
     */
    $LEVELS = array(
        // Admin permissions, 5 is the highest level
        'ADD_USER' => 5,
        'DELETE_USER' => 5,
        'SET_USER_DATA' => 5,
        'CHANGE_DOMAIN_ACCESS' => 5,
        'GET_USERS_LIST' => 5,

        'CHANGE_SETTINGS' => 3,
        'REMOVE_TAG' => 3,
        'SET_RECORD_LIMIT' => 3 ,

        'SHARE_RECORDING' => 2,
    );

    /**
     * Checks whether the current logged in user has permission to do the given $action
     * @param  String $action The name of the permission to check
     * @throws Exception
     */
    function checkPermission($action) {
        global $LEVELS;
        global $level;
        if($level < $LEVELS[$action]) throw new Exception("You do not have permissions to [$action]!");
    }
?>
