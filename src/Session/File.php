<?php

namespace Abimo\Session;

class File implements \SessionHandlerInterface
{
    /**
     * @return mixed
     */
    public function close()
    {
        // TODO: Implement close() method.
    }

    /**
     * @param string $session_id
     * @return mixed
     */
    public function destroy($session_id)
    {
        // TODO: Implement destroy() method.
    }

    /**
     * @param int $maxlifetime
     * @return mixed
     */
    public function gc($maxlifetime)
    {
        // TODO: Implement gc() method.
    }

    /**
     * @param string $save_path
     * @param string $session_id
     * @return mixed
     */
    public function open($save_path, $session_id)
    {
        // TODO: Implement open() method.
    }

    /**
     * @param string $session_id
     * @return mixed
     */
    public function read($session_id)
    {
        // TODO: Implement read() method.
    }

    /**
     * @param string $session_id
     * @param string $session_data
     * @return mixed
     */
    public function write($session_id, $session_data)
    {
        // TODO: Implement write() method.
    }
}