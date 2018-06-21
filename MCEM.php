<?php
/**
 * Class MCEM
 * Just a simple login class
 * Add this file on top of your file where you need the login auth
 */
class MCEM {
    CONST USERNAME = 'test@test.nl';
    CONST PASSWORD = '123456';

	static private $sessRef = false;

	static private $request = [];

	final private function __construct() {

	}

	/**
	 * createSessionReference
	 * refer sessionref to PHP $_SESSION global
	 */
	final private static function createSessionReference() {
		@session_start();
		// Create session reference
		if ( isset( $_SESSION ) ) {
			self::$sessRef = &$_SESSION;
		} else {
			self::$sessRef = [];
		}
	}

	/**
	 * check
	 * start session
	 * check if user has logged in, show login screen if not logged in
	 */
	final public static function check() {
		self::createSessionReference();
		self::initRequest();

		if(isset(self::$request['logout'])){
		    self::sessionDestroy(true);
        }

		if ( isset( self::$request['username'] ) && isset( self::$request['password'] ) ) {
			if ( self::$request['username'] == self::USERNAME && self::$request['password'] == self::PASSWORD) {
				self::$sessRef['user'] = self::$request['username'];
			}
		}

		if ( ! isset( self::$sessRef['user'] ) ) {
			self::showLogin();
		}

		self::showLogout();
	}

	/**
     * showLogin
	 * Show login page
	 */
	final private static function showLogin() {
		; ?>
        <html>
        <head>
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:700,600' rel='stylesheet' type='text/css'>

            <style>
                body {
                    font-family: 'Open Sans', sans-serif;
                    background: #3498db;
                    margin: 0 auto 0 auto;
                    width: 100%;
                    text-align: center;
                    margin: 20px 0px 20px 0px;
                }

                p {
                    font-size: 12px;
                    text-decoration: none;
                    color: #ffffff;
                }

                h1 {
                    font-size: 1.5em;
                    color: #525252;
                }

                .box {
                    background: white;
                    width: 300px;
                    border-radius: 6px;
                    margin: 0 auto 0 auto;
                    padding: 0px 0px 70px 0px;
                    border: #2980b9 4px solid;
                }

                .email {
                    background: #ecf0f1;
                    border: #ccc 1px solid;
                    border-bottom: #ccc 2px solid;
                    padding: 8px;
                    width: 250px;
                    color: #AAAAAA;
                    margin-top: 10px;
                    font-size: 1em;
                    border-radius: 4px;
                }

                .password {
                    border-radius: 4px;
                    background: #ecf0f1;
                    border: #ccc 1px solid;
                    padding: 8px;
                    width: 250px;
                    font-size: 1em;
                }

                .btn {
                    background: #2ecc71;
                    padding-top: 5px;
                    padding-bottom: 5px;
                    color: white;
                    border-radius: 4px;
                    border: #27ae60 1px solid;
                    margin-top: 20px;
                    margin-bottom: 20px;
                    margin-left: 25px;
                    margin-right: 25px;
                    font-weight: 800;
                    font-size: 0.8em;
                }

                .btn:hover {
                    background: #2CC06B;
                }

                #btn2 {
                    float: left;
                    background: #3498db;
                    width: 125px;
                    padding-top: 5px;
                    padding-bottom: 5px;
                    color: white;
                    border-radius: 4px;
                    border: #2980b9 1px solid;

                    margin-top: 20px;
                    margin-bottom: 20px;
                    margin-left: 10px;
                    font-weight: 800;
                    font-size: 0.8em;
                }

                #btn2:hover {
                    background: #3594D2;
                }
            </style>
        </head>
        <body>
        <form method="post" action="<?php echo str_replace( 'index.php', '', $_SERVER['PHP_SELF'] ); ?>">
            <div class="box">
                <h1>MailCamp<br/>Configurator</h1>

                <input type="text" name="username" placeholder="gebruiker" onFocus="field_focus(this, 'email');"
                       onblur="field_blur(this, 'email');" class="email"/>

                <input type="password" name="password" placeholder="password" onFocus="field_focus(this, 'email');"
                       onblur="field_blur(this, 'email');" class="email"/>

                <a href="#">
                    <div class="btn">Login</div>
                </a> <!-- End Btn -->

            </div> <!-- End Box -->

        </form>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
        <script>
            function field_focus(field, email) {
                if (field.value == email) {
                    field.value = '';
                }
            }

            function field_blur(field, email) {
                if (field.value == '') {
                    field.value = email;
                }
            }

            //Fade in dashboard box
            $(document).ready(function () {
                $('.box').hide().fadeIn(1000);
            });

            //Stop click event
            $('a').click(function (event) {
                event.preventDefault();
                $('form').submit();
            });
        </script>
        </body>
        </html>
		<?php
		exit;
	}

	/**
	 * initRequest
     * asign the $_POST variable to our request var
	 */
	final private static function initRequest() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			self::$request = $_POST;
		}
	}

	/**
     * sessionGet
	 * @param string $key
	 * @param null $default
	 *
	 * @return bool|null
	 */
	final public static function sessionGet( $key = '', $default = null ) {
		if ( $key === '' ) {
			return self::$sessRef;
		}
		if ( ! isset( self::$sessRef[ $key ] ) ) {
			return $default;
		}

		return self::$sessRef[ $key ];
	}

	/**
     * sessionSet
	 * @param $key
	 * @param $value
	 */
	final public static function sessionSet( $key, $value ) {
		self::$sessRef[ $key ] = $value;
	}

	/**
     * sessionDestroy
	 * @param bool $redirect
	 */
	final static public function sessionDestroy($redirect = false) {
		self::$sessRef = [];

		if ( session_id() ) {
			@session_destroy();
		}

		if($redirect){
		    header('Location: ' . str_replace( 'index.php', '', $_SERVER['PHP_SELF'] )); exit;
        }
	}

	/**
	 * showLogout
     * add login on top of the screen
	 */
	final public static function showLogout() {
		;?>
        <form method="post" action="<?php echo str_replace( 'index.php', '', $_SERVER['PHP_SELF'] ); ?>">
            <input type="hidden" name="logout">
            <input type="submit" value="Log uit">
            </div> <!-- End Box -->
        </form>
        <?php
	}

}

MCEM::check();