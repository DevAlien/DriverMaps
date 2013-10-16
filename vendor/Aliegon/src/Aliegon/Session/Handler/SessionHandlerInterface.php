<?php
/**
 * Interface for Session Handlers
 *
 * Interface for Session Handlers, if we want to implement a new session handler, with memecahced, for example, you have to implement this interface
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright anecms.com (C) 2008-2012
 * @license LGPL(V3) http://www.opensource.org/licenses/lgpl-3.0.html
 * @version 1.0
 */

namespace Aliegon\Session\Handler;

/**
 * Interface for Session Handlers
 *
 * Interface for Session Handlers, if we want to implement a new session handler, with memecahced, for example, you have to implement this interface
 *
 * @author Goncalo Margalho <gsky89@gmail.com>
 * @copyright anecms.com (C) 2008-2012
 * @version 1.0
 */
interface SessionHandlerInterface {

	/**
	 * The construct will prepare everything to handle the session
	 *
	 * @param Config $config Config object
	 */
    public function __construct(\Aliegon\Config $config);

    /**
     * Re-initialize existing session, or creates a new one. Called when a session starts or when session_start() is invoked.
     *
     * @param string $savePath The path where to store/retrieve the session
     * @param string $sessionName The session id
     * @return boolean The return value (usually TRUE on success, FALSE on failure). Note this value is returned internally to PHP for processing.
     */
    public function open($savePath, $sessionName);

    
    /**
     * Closes the current session. This function is automaticaly executed when closing the session, or explicitly via session_write_close().
     * @return boolean The return value (usually TRUE on success, FALSE on failure). Note this value is returned internally to PHP for processing.
     */
    public function close();

    /**
     * Reads the session data from the session storage, and returns the results.
     *
     * @param string $sessionID The session id.
     * @return Mixed Returns an encoded string of the read data. If nothing was read, it must return an empty string. Note this value is returned internally to PHP for processing.
     */
    public function read($sessionID);

    /**
     * Writes the session data to the session storage.
     *
     * @param string $sessionID The session id.
     * @param string $data The encoded session data.
     * @return boolean The return value (usually TRUE on success, FALSE on failure). Note this value is returned internally to PHP for processing. 
     */
    public function write($sessionID, $data);

    /**
     * estroys a session. Called by session_regenerate_id() (with $destroy = TRUE), session_destroy() and when session_decode() fails.
     *
     * @param string $sessionID The session ID being destroyed.
	 * @return boolean The return value (usually TRUE on success, FALSE on failure). Note this value is returned internally to PHP for processing.
     */
    public function destroy($sessionID);

    /**
     * Cleans up expired sessions. Called by session_start(), based on session.gc_divisor, session.gc_probability and session.gc_lifetime settings.
     *
     * @param string $maxlifetimeSessions that have not updated for the last maxlifetime seconds will be removed.
     * @return boolean The return value (usually TRUE on success, FALSE on failure). Note this value is returned internally to PHP for processing.
     */
    public function gc($maxlifetime);

}