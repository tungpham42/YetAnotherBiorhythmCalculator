var userTrackAdminPanel = (function () {

    var mUser, panel, userList;

    function setUser(user) {
        mUser = user;
    }

    function setPanelDOM(mpanel) {
        panel = mpanel;
    }

    function getUserList() {
        userTrackAjax.getUsersList(displayUserList);
    }

    function displayUserList(data) {        
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

    function bindEvents() {

        bindToggle();
        bindChangePassword();
        bindEditable();
        bindAddUser();
        bindAddRemoveDomain();
    }

    //Display the toggle button for admins
    function bindToggle() {

        if (mUser.level < 5) {

            jQuery('#toggle', panel).text('');
            jQuery('#adminBar', panel).click(function () {
                alert("You do not have admin rights.");
            });

            return;
        }

        //Toggle Admin Panel
        jQuery('#adminBar', panel).click(function () {

            panel.toggleClass("toggle", 500);

            if (panel.hasClass('toggle')) {
                jQuery('#toggle', panel).text('△');
                jQuery('#adminPanelWrap', panel).stop(0,1).slideDown(100);
                
            } else {
                jQuery('#toggle', panel).text('▽');
                jQuery('#adminPanelWrap', panel).stop(0,1).slideUp(500);
            }

            //Save the toggle position in case of page refresh
            localStorage.toggled = panel.hasClass('toggle');
        });

        //Auto-toggle on refresh
        if (localStorage.toggled == 'true') {
            jQuery('#adminBar', panel).trigger('click');
            //Scroll to bottom
            jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, "slow");
        }
    }

    //Username and level can be edited by clicking the td
    function bindEditable() {

        //Only focus if users clicks on input
        jQuery(panel).on('click', 'input', function (e) {
            e.stopPropagation();
        });

        //Change text to editable input
        jQuery(panel).on('click', '.editable', function () {

            var input = jQuery('input', this);
            //If we are not already editing
            if (input.length === 0 ){
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
                if(dataType == "Username" && newContent == "") {
                    userTrackAjax.deleteUser(userId);
                }

                userTrackAjax.setUserData(dataType, newContent, userId);
            }


        });

    }

    function bindChangePassword() {
        jQuery('#userList').on('click','.changePassword', function () {

            var userId = jQuery(this).parents('tr').find('td:first-child').text();
            var pass = prompt("Password: ");
            var username = jQuery(this).parents('tr').find('td').eq(1).text();

            if(pass === null || pass == undefined) return;

            if (pass.length > 3 && userId !== undefined) {
                // Logout if changing our own password
                var shouldLogout = username === $('#adminName').text();
                userTrackAjax.setUserData("password", pass, userId, shouldLogout);
            } else {
                alert("Password too short or something went wrong...");
            }
        });
    }

    function bindAddUser() {
        jQuery('#addUser').on('click', function () {

            var userName = prompt("User name: ");
            var pass = prompt("Password: ");

            if (userName !== undefined && pass !== undefined && userName.length > 2) {
                
                userTrackAjax.addUser(userName, pass);
            }
        });
    
    }

    function bindAddRemoveDomain() {

        jQuery(panel).on('click', '.addDomain', function () {
            var userId = jQuery(this).parents('tr').find('td:first-child').text();
            alertify.defaultValue(window.location.host)
                .prompt("Domain name: ", function(domain, ev) {
                ev.preventDefault();

                if (domain === undefined || domain.length < 1)
                    return;

                domain = nowwwDomain(domain);
                userTrackAjax.changeUserAccess('add', domain, userId);
            });
        });


        jQuery(panel).on('dblclick', '.removeDomain', function () {
            var userId = jQuery(this).parents('tr').find('td:first-child').text();
            var domain = jQuery(this).parents('li').children('span').text();

            userTrackAjax.changeUserAccess('remove', domain, userId);
        });

    }

    // Private functions
    function nowwwDomain(domain) {

        // Remove http
        if (domain.indexOf('http://') != -1)
            domain = domain.substr('http://'.length);

        // Remove https
        if (domain.indexOf('https://') != -1)
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
        getUserList: getUserList,
    };

}());