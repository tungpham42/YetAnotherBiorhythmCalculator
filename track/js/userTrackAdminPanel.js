/* global UST */
'use script';

window.userTrackAdminPanel = (function () {

    var mUser, panel, userList;

    function setUser (user) {
        mUser = user;
    }

    function setPanelDOM (mpanel) {
        panel = mpanel;
    }

    function getUserList () {
        UST.API.getUsersList(displayUserList);
    }

    function displayUserList (data) {        
        userList = data;
        jQuery('#userTemplate', panel).tmpl(userList).appendTo('#userList');

        //Rebind tooltip
        jQuery("li img[title]").qtip({
            content: {
                attr: 'title'
            },
            style: {
                classes: 'qtip-rounded qtip-red tooltip'
            },
            position: {
                target: 'mouse',
                adjust: {
                    y: 20,
                    x: 20
                },
                viewport: jQuery(window)
            }
        });
    }

    function bindEvents () {

        bindToggle();
        bindChangePassword();
        bindEditable();
        bindAddUser();
        bindAddRemoveDomain();
    }

    //Display the toggle button for admins
    function bindToggle () {

        if (mUser.level < 5) {

            jQuery('#toggle', panel).text('');
            jQuery('#adminBar', panel).click(function () {
                alertify.alert("You do not have admin rights.");
            });

            return;
        }

        //Toggle Admin Panel
        jQuery('#adminBar', panel).click(function () {

            panel.toggleClass("toggle", 500);

            if (panel.hasClass('toggle')) {
                jQuery('#toggle', panel).text('△');
                jQuery('#adminPanelWrap', panel).stop(0,1).slideDown(100);
                jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, "slow");
                
            } else {
                jQuery('#toggle', panel).text('▽');
                jQuery('#adminPanelWrap', panel).stop(0,1).slideUp(500);
                jQuery("html, body").animate({ scrollTop: 0 }, "slow");

            }

            //Save the toggle position in case of page refresh
            localStorage.toggled = panel.hasClass('toggle');
        });

        // Auto-toggle on refresh
        // if (localStorage.toggled === 'true') {
        //     jQuery('#adminBar', panel).trigger('click');
        //     //Scroll to bottom
        //     jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, "slow");
        // }
    }

    //Username and level can be edited by clicking the td
    function bindEditable () {

        //Only focus if users clicks on input
        jQuery(panel).on('click', 'input', function (e) {
            e.stopPropagation();
        });

        //Change text to editable input
        jQuery(panel).on('click', '.editable', function () {

            var input = jQuery('input', this);
            //If we are not already editing
            if (input.length === 0) {
                var prevContent = jQuery(this).text();

                input = jQuery('<input type="text" />');
                input.val(prevContent);

                input.blur(function () {
                    jQuery(this).parent().trigger('click');
                });

                jQuery(this).html(input);
                input.focus();
            }

            // Else save updated data
            else {

                var newContent = input.val();
                var dataType = jQuery(this).closest('table').find('th').eq(jQuery(this).index()).text();

                jQuery(this).text(newContent);
                var userId = jQuery(this).parents('tr').find('td:first-child').text();

                // Delete if user name is set to empty string
                if(dataType === "Username" && newContent === "") {
                    UST.API.deleteUser(userId);
                }

                UST.API.setUserData(dataType, newContent, userId);
            }


        });

    }

    function bindChangePassword () {
        jQuery('#userList').on('click','.changePassword', function () {

            var userId = jQuery(this).parents('tr').find('td:first-child').text();
            var username = jQuery(this).parents('tr').find('td').eq(1).text();
            alertify.prompt("Enter new password (min 3 characters): ", function (pass) {
                if(pass === null || pass === undefined) return;

                if (pass.length <= 3 || !userId) {
                    alertify.alert("Password too short or something else went wrong...");
                    return;
                }

                // Logout if changing our own password
                var shouldLogout = username === jQuery('#adminName').text();
                UST.API.setUserData("password", pass, userId, shouldLogout);
            });

        });
    }

    function bindAddUser () {
        jQuery('#addUser').on('click', function () {

            alertify.prompt("User name: ", function (username) {
                alertify.prompt("Password:", function (pass) {
                    if (username !== undefined && pass !== undefined && username.length > 2) {
                        UST.API.addUser(username, pass);
                        alertify.log("New user was added.");
                    } else {
                        alertify.error("User was not added.");
                    }
                });
            });
        });
    }

    function bindAddRemoveDomain () {

        jQuery(panel).on('click', '.addDomain', function () {
            var userId = jQuery(this).parents('tr').find('td:first-child').text();
            alertify.defaultValue(window.location.host)
                .prompt("Domain name: ", function (domain, ev) {
                    ev.preventDefault();

                    if (domain === undefined || domain.length < 1)
                        return;

                    domain = nowwwDomain(domain);
                    UST.API.changeUserAccess('add', domain, userId);
                });
        });


        jQuery(panel).on('click', '.removeDomain', function () {
            var domain = jQuery(this).parents('li').children('span').text();
            var userId = jQuery(this).parents('tr').find('td:first-child').text();
            alertify.confirm('Remove access to <b>' + domain + '</b> for this user?', function() {
                UST.API.changeUserAccess('remove', domain, userId);
            });
        });

    }

    // Private functions
    function nowwwDomain (domain) {

        // Remove http
        if (domain.indexOf('http://') !== -1)
            domain = domain.substr('http://'.length);

        // Remove https
        if (domain.indexOf('https://') !== -1)
            domain = domain.substr('https://'.length);

        // Remove www. if exists
        if (domain.indexOf('www.') === 0)
            domain = domain.substr(4);

        // Remove trailing slash
        if (domain[domain.length - 1] === '/')
            domain = domain.slice(0, -1);
        
        return domain;
    }


    //Public facade
    return {
        setUser: setUser,
        setPanel: setPanelDOM,
        bindEvents: bindEvents,
        getUserList: getUserList
    };
}());
