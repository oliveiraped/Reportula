<?php namespace Orchestra\Support;

use Orchestra\Support\Ftp\Morph as Facade;

class Ftp
{
    /**
     * FTP host.
     *
     * @var string
     */
    protected $host = null;

    /**
     * FTP port.
     *
     * @var integer
     */
    protected $port = 21;

    /**
     * FTP user.
     *
     * @var string
     */
    protected $user = null;

    /**
     * FTP password.
     *
     * @var string
     */
    protected $password = null;

    /**
     * FTP stream connection.
     *
     * @var Stream
     */
    protected $connection = null;

    /**
     * FTP timeout.
     *
     * @var integer
     */
    protected $timeout = 90;

    /**
     * FTP passive mode flag
     *
     * @var boolean
     */
    protected $passive = false;

    /**
     * SSL-FTP connection flag.
     *
     * @var boolean
     */
    protected $ssl = false;

    /**
     * System type of FTP server.
     *
     * @var string
     */
    protected $systemType;

    /**
     * Make a new FTP instance.
     *
     * @param  array    $config
     * @return self
     */
    public static function make($config = array())
    {
        return new static($config);
    }

    /**
     * Make a new FTP instance.
     *
     * @param  array    $config
     * @return void
     */
    public function __construct($config = array())
    {
        if (! empty($config)) {
            $this->setUp($config);
        }
    }

    /**
     * Configure FTP.
     *
     * @param  array    $config
     * @return void
     */
    public function setUp($config = array())
    {
        $host = isset($config['host']) ? $config['host'] : null;

        if (preg_match('/^(ftp|sftp):\/\/([a-zA-Z0-9\.\-_]*):?(\d{1,4})$/', $host, $matches)) {
            $config['host'] = $matches[2];
            $config['ssl']  = ($matches[1] === 'sftp' ? true : false);

            if (isset($matches[3])) {
                $config['port'] = $matches[3];
            }
        }

        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Change current directory on FTP server.
     *
     * @param  string   $directory
     * @return boolean
     */
    public function changeDirectory($directory)
    {
        return @Facade::fire('chdir', array($this->connection, $directory));
    }

    /**
     * Get current directory path.
     *
     * @return string
     */
    public function currentDirectory()
    {
        return @Facade::pwd($this->connection);
    }

    /**
     * Download file from FTP server.
     *
     * @param  string   $remoteFile
     * @param  string   $localFile
     * @param  integer  $mode
     * @return boolean
     */
    public function get($remoteFile, $localFile, $mode = FTP_ASCII)
    {
        return @Facade::fire('get', array($this->connection, $localFile, $remoteFile, $mode));
    }

    /**
     * Upload file to FTP server.
     *
     * @param  string   $localFile
     * @param  string   $remoteFile
     * @param  integer  $mode
     * @return boolean
     */
    public function put($localFile, $remoteFile, $mode = FTP_ASCII)
    {
        return @Facade::fire('put', array($this->connection, $remoteFile, $localFile, $mode));
    }

    /**
     * Rename file on FTP server.
     *
     * @param  string   $oldName
     * @param  string   $newName
     * @return boolean
     */
    public function rename($oldName, $newName)
    {
        return @Facade::fire('rename', array($this->connection, $oldName, $newName));
    }

    /**
     * Delete file on FTP server.
     *
     * @param  string   $remoteFile
     * @return boolean
     */
    public function delete($remoteFile)
    {
        return @Facade::fire('delete', array($this->connection, $remoteFile));
    }

    /**
     * Set file permissions.
     *
     * @param  string   $remoteFile
     * @param  integer  $permissions    For example: 0644
     * @return boolean
     * @throws \RuntimeException        If unable to chmod $remoteFile
     */
    public function permission($remoteFile, $permission = 0644)
    {
        return @Facade::fire('chmod', array($this->connection, $permission, $remoteFile));
    }

    /**
     * Get list of files/directories on FTP server.
     *
     * @param  string   $directory
     * @return array
     */
    public function allFiles($directory)
    {
        $list = @Facade::fire('nlist', array($this->connection, $directory));

        return is_array($list) ? $list : array();
    }

    /**
     * Create directory on FTP server.
     *
     * @param  string   $directory
     * @return boolean
     */
    public function makeDirectory($directory)
    {
        return @Facade::fire('mkdir', array($this->connection, $directory));
    }

    /**
     * Remove directory on FTP server.
     *
     * @param  string   $directory
     * @return boolean
     */
    public function removeDirectory($directory)
    {
        return @Facade::fire('rmdir', array($this->connection, $directory));
    }

    /**
     * Connect to FTP server.
     *
     * @return boolean
     * @throws \Orchestra\Support\Ftp\Exception If unable to connect/login
     *                                          to FTP server.
     */
    public function connect()
    {
        if (is_null($this->host)) {
            return ;
        }

        $this->createConnection();

        if (! (@Facade::login($this->connection, $this->user, $this->password))) {
            throw new Ftp\ServerException("Failed FTP login to [{$this->host}].");
        }

        // Set passive mode.
        @Facade::pasv($this->connection, (bool) $this->passive);

        // Set system type.
        $this->systemType = @Facade::systype($this->connection);

        return true;
    }

    /**
     * Create a FTP connection.
     *
     * @return void
     * @throws \Orchestra\Support\Ftp\Exception If unable to connect to FTP
     *                                          server.
     */
    protected function createConnection()
    {
        if ($this->ssl and @Facade::isCallable('sslConnect')) {
            if (! ($this->connection = @Facade::sslConnect($this->host, $this->port, $this->timeout))) {
                throw new Ftp\ServerException(
                    "Failed to connect to [{$this->host}] (SSL Connection)."
                );
            }
        } elseif (! ($this->connection = @Facade::connect($this->host, $this->port, $this->timeout))) {
            throw new Ftp\ServerException("Failed to connect to [{$this->host}].");
        }
    }

    /**
     * Close FTP connection.
     *
     * @return void
     * @throws \RuntimeException If unable to close connection.
     */
    public function close()
    {
        if (! is_null($this->connection)) {
            @Facade::close($this->connection);
            $this->connection = null;
        }
    }

    /**
     * Check FTP connection status.
     *
     * @return boolean
     */
    public function connected()
    {
        return ( ! is_null($this->connection));
    }
}
