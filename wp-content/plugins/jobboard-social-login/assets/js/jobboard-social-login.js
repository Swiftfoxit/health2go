(function ($) {
    "use strict";

    $(window).on('load', function () {
        load_social();
    });

    function load_social() {
        $('.jobboard-social-login').on('click', '.social-facebook', function () {

            if (jb_social_login.facebook == undefined || jb_social_login.facebook == '') {
                jobboard_create_notices($(this).data('notice'), 'error');
                return false;
            }

            FB.init({
                appId: jb_social_login.facebook,
                cookie: true,  // enable cookies to allow the server to access
                xfbml: true,  // parse social plugins on this page
                version: 'v2.8' // use graph api version 2.8
            });

            FB.login(function (response) {
                if (response.status != 'connected') {
                    return false;
                }

                FB.api('/me?fields=id,name,email,first_name,last_name', function (response) {
                    login({
                        user_login: response.id,
                        user_email: response.email,
                        display_name: response.name,
                        first_name: response.first_name,
                        last_name: response.last_name,
                        user_image: 'https://graph.facebook.com/' + response.id + '/picture?type=normal'
                    });
                });
            });
        });

        if (jb_social_login.google) {
            gapi.load('auth2', function () {
                var auth2 = gapi.auth2.init({
                    'client_id': jb_social_login.google
                });

                $('.jobboard-social-login .social-google').each(function () {
                    auth2.attachClickHandler($(this).get(0), {}, google_login);
                });
            });
        } else {
            $('.jobboard-social-login').on('click', '.social-google', function () {
                jobboard_create_notices($(this).data('notice'), 'error');
                return false;
            });
        }

        if (jb_social_login.linkedin) {
            $('.jobboard-social-login').on('click', '.social-linkedin', function () {

                IN.init({
                    api_key: jb_social_login.linkedin,
                    authorize: true,
                    onLoad: "onLinkedInLoad"
                });

                if (IN.User) {
                    IN.User.authorize(function () {
                        // callback();
                    });
                }

            });
        }
    }

    var google_login = function (user) {
        var profile = user.getBasicProfile();
        login({
            user_login: profile.getId(),
            user_email: profile.getEmail(),
            display_name: profile.getName(),
            first_name: profile.getGivenName(),
            last_name: profile.getFamilyName(),
            user_image: profile.getImageUrl()
        });
    };

})(jQuery);

// Setup an event listener to make an API call once auth is complete
function onLinkedInLoad() {

    IN.User.authorize(function () {
        // callback();
    });

    IN.Event.on(IN, "auth", getProfileData);
}

// Handle the successful return from the API call
function onSuccess(data) {
    login({
        user_login: data.id,
        user_email: data.emailAddress,
        display_name: data.formattedName,
        first_name: data.firstName,
        last_name: data.lastName,
        user_image: data.pictureUrl,
        user_meta: {
            user_country: data.location.name,
            description: data.summary,
            'social-linkedin': data.publicProfileUrl
        }
    });
}

// Handle an error response from the API call
function onError(error) {
    console.log(error);
}

// Use the API call wrapper to request the member's basic profile data
function getProfileData() {
    IN.API.Raw("/people/~:(id,email-address,first-name,last-name,formatted-name,picture-url,location,public-profile-url,summary)?format=json").result(onSuccess).error(onError);
}

function login(profile) {
    jQuery.post(jb_social_login.ajaxurl, {'action': 'jb_social_login', 'profile': profile}, function (response) {
        if (jb_social_login.debug == true) {
            console.log(response);
        }
        location.reload();
    });
}