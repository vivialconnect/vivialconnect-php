<?

namespace VivialConnect\Resources;

class SubResource extends Resource
{


    protected function processConnectorNumber(array $attributes = [])
    {
        /* pop the connector id */
        array_pop($attributes);
        return ["connector" => ["phone_numbers" => [$attributes]]];
    }

    protected function processConnectorCallback(array $attributes = [])
    {
        /* pop the connector id */
        array_pop($attributes);
        return ["connector" => ["callbacks" => [$attributes]]];
   }


    /**
     * Get the full resource URI
     *
     * @return string
     */
    public function getResourceUri()
    {
        $uri = sprintf(self::API_ACCOUNT_PREFIX,
            self::getCredentialToken(self::API_ACCOUNT_ID));
        $uri .= "connectors/";
        $connector_id = $this->connector_id;

        if ($this->_singular == 'connectornumber') {
            $uri .= "{$connector_id}/phone_numbers.json";
            return $uri;
        }

        elseif ($this->_singular == 'connectorcallback') {
            $uri .= "{$connector_id}/callbacks.json";
            return $uri;
        }
    }

    /**
     * Wraps attributes into VivialConnect compatible payload.
     *
     * @param $attributes
     * @param $root
     * @return array
     */
    protected function wrapAttributes(array $attributes = [], $root = 'object')
    {
        if ($root == "connectornumber") {
            return $this->processConnectorNumber($attributes);
        }

        elseif ($root == "connectorcallback"){
            return $this->processConnectorCallback($attributes);
        }
    }

}