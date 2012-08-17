<?php

/**
 * 2012-3-7 11:12:11
 * @package package_name
 * @version 1.0
 *
 * @author hadoop <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 *
 */
class Neo4j {

    private $ch;
    private $tempFile;
    private $neo4jRest;
    private $errorCode;
    private $errorMsg;

    /**
     * 初始化
     */
    public function __construct($restUrl) {
        $this->ch = curl_init();
        $this->neo4jRest = $restUrl;
    }

    /**
     * 获取错误代码
     */
    public function getErrorCode() {
        return $this->errorCode;
    }

    /**
     * 获取错误消息
     */
    public function getErrorMsg() {
        return $this->errorMsg;
    }

    /**
     * Get service root
     * GET /
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/
     * Response
     * 	200
     * 	{
     * 		"node" : "http://localhost:7474/db/data/node",
     * 		"node_index" : "http://localhost:7474/db/data/index/node",
     * 		"relationship_index" : "http://localhost:7474/db/data/index/relationship",
     * 		"reference_node" : "http://localhost:7474/db/data/node/0",
     * 		"extensions_info" : "http://localhost:7474/db/data/ext",
     * 		"extensions" : {
     * 		}
     * 	}
     * @return mix
     */
    public function getRoot() {
        list($this->errorCode, $data) = $this->curlRequest();
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        }
        return false;
    }

    /**
     * Create empty node
     * POST /node
     * Example using curl
     * 	curl -H Accept:application/json -X POST http://localhost:7474/db/data/node
     * Response
     * 	201: OK, a node was created
     * 	Location: http://localhost:7474/db/data/node/123
     * @return boolean
     */
    public function createEmptyNode() {
        list($this->errorCode, $data) = $this->curlRequest('node', null, 'POST');
        if ($this->errorCode == 201) {
            $this->errorMsg = 'OK, a node was created';
            return true;
        }
        return false;
    }

    /**
     * Create node with properties
     * POST/node
     * 	{"name": "Thomas Anderson","profession": "Hacker"}
     * Example using curl
     * 	curl -H Accept:application/json -H Content-Type:application/json -X POST -d '{"name": "Thomas Anderson", "profession": "Hacker"}' http://localhost:7474/db/data/node
     * Response
     * 	201: OK, a node was created
     * 	Location: http://localhost:7474/db/data/node/123
     * 	400: Invalid data sent
     * @param array $data
     * @return int|boolean
     */
    public function createNodeWithProperties($data) {
        list($this->errorCode, $data) = $this->curlRequest('node', $data, 'POST');
        if ($this->errorCode == 201) {
            $this->errorMsg = 'OK, a node was created';
            return end(explode('/', $data->self));
        } elseif ($this->errorCode == 400) {
            $this->errorMsg = 'Invalid data sent';
        }
        return false;
    }

    /**
     * Get node
     * GET /node/123
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/node/123
     * Response
     * 	200: OK
     * 	{
     * 		"self": "http://localhost:7474/db/data/node/123",
     * 		"data": { "name": "Thomas Anderson","age": 29},
     * 		"create_relationship": "http://localhost:7474/db/data/node/123/relationships"
     * 		"all_relationships": "http://localhost:7474/db/data/node/123/relationships/all",
     * 		"all_typed relationships": "http://localhost:7474/db/data/node/123/relationships/all/{-list|&|types}",
     * 		"incoming_relationships": "http://localhost:7474/db/data/node/123/relationships/in",
     * 		"incoming_typed relationships": "http://localhost:7474/db/data/node/123/relationships/in/{-list|&|types}",
     * 		"outgoing_relationships": "http://localhost:7474/db/data/node/123/relationships/out",
     * 		"outgoing_typed relationships": "http://localhost:7474/db/data/node/123/relationships/out/{-list|&|types}",
     * 		"properties": "http://localhost:7474/db/data/node/123/properties",
     * 		"property": "http://localhost:7474/db/data/node/123/property/{key}",
     * 		"traverse": "http://localhost:7474/db/data/node/123/traverse/{returnType}"
     * 	}
     * 	404: Node not found
     * @param int $nid
     * @param string $selected
     * @return mix
     */
    public function getNode($nid) {
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid, null, 'GET');
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Node not found';
        }
        return false;
    }

    /**
     * Delete node,Nodes with relationships can not be deleted
     * DELETE /node/123
     * Example using curl
     * 	curl -X DELETE http://localhost:7474/db/data/node/123
     * Response
     * 	204: OK, no content returned
     * 	404: Node not found
     * 	409: Node could not be deleted (still has relationships?)
     * @param int $nid
     * @return boolean
     */
    public function deleteNode($nid) {
        list($this->errorCode, ) = $this->curlRequest('node/' . $nid, null, 'DELETE');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Node not found';
        } elseif ($this->errorCode == 409) {
            $this->errorMsg = 'Node could not be deleted (still has relationships?)';
        }
        return false;
    }

    /**
     * Set properties on node
     * Replaces all properties on a node with the supplied set of properties.
     * PUT /node/123/properties
     * 	{ "name": "Thomas Anderson","profession": "Hacker"}
     * Example using curl
     * 	curl -H Content-Type:application/json -X PUT -d '{"name": "Thomas Anderson", "profession": "Hacker"}' http://localhost:7474/db/data/node/123/properties
     * Response
     * 	204: OK, no content returned
     * 	400: Invalid data sent
     * 	404: Node node found
     * @param int $nid
     * @param array $data
     * @return boolean
     */
    public function setPropertiesOnNode($nid, $data) {
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/properties', $data, 'PUT');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        } elseif ($this->errorCode == 400) {
            $this->errorMsg = 'Invalid data sent';
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Node node found';
        }
        return false;
    }

    /**
     * Get properties on node
     * GET /node/123/properties
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/node/123/properties
     * Response
     * 	200: OK
     * 	{"name": "Thomas Anderson","profession": "Hacker"}
     * 	204: OK, no properties found
     * 	404: Node not found
     * @param int $nid
     * @return mix
     */
    public function getPropertiesOnNode($nid) {
        list($this->errorCode, $data) = $this->getNode($nid, 'properties');
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        } elseif ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no properties found';
            return true;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Node not found';
        }
        return false;
    }

    /**
     * Remove properties from node
     * 	Removes all properties from a node.
     * DELETE /node/123/properties
     * Example using curl
     * 	curl -X DELETE http://localhost:7474/db/data/node/123/properties
     * Response
     * 	204: OK, no content returned
     * 	404: Node not found
     * @param int $nid
     * @return boolean
     */
    public function removePropertiesFromNode($nid) {
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/properties', null, 'delete');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Node not found';
        }
        return false;
    }

    /**
     * Set property on node
     * PUT /node/123/properties/foo
     * 	"the_value"
     * Example using curl
     * 	curl -H Content-Type:application/json -X PUT -d '"the_value"' http://localhost:7474/db/data/node/123/properties/foo
     * Response
     * 	204: OK, no content returned
     * 	400: Invalid data sent
     * @param int $nid
     * @param string $property
     * @param string $value
     * @return boolean
     */
    public function setPropertyOnNode($nid, $propertyName, $value) {
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/properties/' . $propertyName, $value, 'PUT');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        } elseif ($this->errorCode == 400) {
            $this->errorMsg = 'Invalid data sent';
        }
        return false;
    }

    /**
     * Get property on node
     * GET /node/123/properties/foo
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/node/123/properties/foo
     * Response
     * 	200: OK
     * 	"the_value"
     * 	404: Node or property not found
     * @param int $nid
     * @param string $property
     * @return mix
     */
    public function getPropertyOnNode($nid, $property) {
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/properties/' . $property);
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Node or property not found';
        }
        return false;
    }

    /**
     * Remove property from node
     * DELETE /node/123/properties/foo
     * Example using curl
     * 	curl -X DELETE http://localhost:7474/db/data/node/123/properties/foo
     * Response
     * 	204: OK, no content returned
     * 	404: Node or property not found
     * @param int $nid
     * @param string $property
     * @return boolean
     */
    public function removePropertyFromNode($nid, $property) {
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/properties/' . $property, null, 'delete');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Node or property not found';
        }
        return false;
    }

    /**
     * Get Relationship by ID
     * GET http://localhost:7474/db/data/relationship/6
     * Accept: application/json
     * Example response
     * 200: OK
     * Content-Type: application/json
     * {
     * 	"start" : "http://localhost:7474/db/data/node/24",
     * 	"data" : {},
     * 	"self" : "http://localhost:7474/db/data/relationship/6",
     * 	"property" : "http://localhost:7474/db/data/relationship/6/properties/{key}",
     * 	"properties" : "http://localhost:7474/db/data/relationship/6/properties",
     * 	"type" : "know",
     * 	"extensions" : {},
     * 	"end" : "http://localhost:7474/db/data/node/23"
     * }
     * @param int $relId
     * @return
     */
    public function getRelationship($relId) {
        list($this->errorCode, $data) = $this->curlRequest('relationship/' . $relId, null, 'GET');
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK, a relationship was created';
            return $data;
        }
        return false;
    }

    /**
     * Create relationship
     * POST /node/123/relationships
     * 	{"to": "http://localhost:7474/db/data/node/152","data": { "date", 1270559208258 },"type": "KNOWS"}
     * Example using curl
     * 	curl -H Accept:application/json -H Content-Type:application/json -X POST -d '{"to": "http://localhost:7474/db/data/node/152", "data": {"date": 1270559208258}, "type": "KNOWS"}' http://localhost:7474/db/data/node/123/relationships
     * Response
     * 	201: OK, a relationship was created
     * 	Location: http://localhost:7474/db/data/relationship/456
     * 	400: Invalid data sent
     * 	404: "to" node, or the node specified by the URI not found
     * @param int $nid
     * @param int $toUid
     * @param string $type
     * @param array $data
     * @return mix
     */
    public function createRelationship($nid, $toUid, $type, $data) {
        $postData = array();
        $postData['to'] = $this->neo4jRest . 'node/' . $toUid;
        $postData['data'] = $data;
        $postData['type'] = $type;
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/relationships', $postData, 'post');
        if ($this->errorCode == 201) {
            $this->errorMsg = 'OK, a relationship was created';
            return true;
        } elseif ($this->errorCode == 400) {
            $this->errorMsg = 'Invalid data sent';
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = '"to" node, or the node specified by the URI not found';
        }
        return false;
    }

    /**
     * Set properties on relationship
     * Replaces all properties on a relationship with the supplied set of properties.
     * PUT /relationship/456/properties
     * 	{ "date": 1270559208258 }
     * Example using curl
     * 	curl -H Content-Type:application/json -X PUT -d '{"date": 1270559208258}' http://localhost:7474/db/data/relationship/456/properties
     * Response
     * 	204: OK, no content returned
     * 	400: Invalid data sent
     * 	404: Relationship node found
     * @param int $relationshipId
     * @param array $data
     * @return array
     */
    public function setPropertiesOnRelationship($relationshipId, $data) {
        list($this->errorCode, $data) = $this->curlRequest('relationship/' . $relationshipId . '/properties', $data, 'put');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
        } elseif ($this->errorCode == 400) {
            $this->errorMsg = 'Invalid data sent';
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Relationship node found';
        }
        return false;
    }

    /**
     * Get properties on relationship
     * GET /relationship/456/properties
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/relationship/456/properties
     * Response
     * 	200: OK
     * 	{ "date": 1270559208258 }
     * 	204: OK, no properties found
     * 	404: Relationship not found
     * @param int $relationShip
     * @return array
     */
    public function getPropertiesOnRelationship($relationshipId) {
        list($this->errorCode, $data) = $this->curlRequest('relationship/' . $relationshipId . '/properties');
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        } elseif ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no properties found';
            return true;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg == 'Relationship not found';
        }
        return false;
    }

    /**
     * Remove properties from relationship
     * Removes all properties from a relationship.
     * DELETE /relationship/456/properties
     * Example using curl
     * 	curl -X DELETE http://localhost:7474/db/data/relationship/456/properties
     * Response
     * 	204: OK, no content returned
     * 	404: Relationship not found
     * @param int $relationshipId
     * @return boolean
     */
    public function removePropertiesFromRelationship($relationshipId) {
        list($this->errorCode, $data) = $this->curlRequest('relationship/' . $relationshipId . '/properties', null, 'delete');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        } elseif ($this->errorMsg == 404) {
            $this->errorMsg = 'Relationship not found';
        }
        return false;
    }

    /**
     * Set property on relationship
     * PUT /relationship/456/properties/foo
     * 	"the_value"
     * Example using curl
     * 	curl -H Content-Type:application/json -X PUT -d '"the_value"' http://localhost:7474/db/data/relationship/456/properties/foo
     * Response
     * 	204: OK, no content returned
     * 	400: Invalid data sent
     * 	404: Relationship not found
     * @param int $relationshipId
     * @param string $property
     * @param string $value
     * @return boolean
     */
    public function setPropertyOnRelationship($relationshipId, $property, $value) {
        list($this->errorCode, $data) = $this->curlRequest('relationship/' . $relationshipId . '/properties/' . $property, $value, 'put');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        } elseif ($this->errorCode == 400) {
            $this->errorMsg = 'Invalid data sent';
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Relationship not found';
        }
        return false;
    }

    /**
     * Get property on relationship
     * GET /relationship/456/properties/foo
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/relationship/456/properties/foo
     * Response
     * 	200: OK
     * 	"the_value"
     * 	404: Relationship or property not found
     * @param int $relationshipId
     * @param string $property
     * @return mix
     */
    public function getPropertyOnRelationship($relationshipId, $property) {
        list($this->errorCode, $data) = $this->curlRequest('relationship/' . $relationshipId . '/properties/' . $property);
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Relationship or property not found';
        }
        return false;
    }

    /**
     * Remove property from relationship
     * DELETE /relationship/456/properties/foo
     * Example using curl
     * 	curl -X DELETE http://localhost:7474/db/data/relationship/456/properties/foo
     * Response
     * 	204: OK, no content returned
     * 	404: Relationship or property not found
     * @param int $relationshipId
     * @param string $property
     * @return boolean
     */
    public function removePropertyFromRelationship($relationshipId, $property) {
        list($this->errorCode, $data) = $this->curlRequest('relationship/' . $relationshipId . '/properties/' . $property, null, 'delete');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Relationship or property not found';
        }
        return false;
    }

    /**
     * Delete relationship
     * DELETE /relationship/456
     * Example using curl
     * 	curl -X DELETE http://localhost:7474/db/data/relationship/456
     * Response
     * 	204: OK, no content returned
     * 	404: Relationship not found
     * @param int $relationshipId
     * @return boolean
     */
    public function deleteRelationship($relationshipId) {
        list($this->errorCode, $data) = $this->curlRequest('relationship/' . $relationshipId, null, 'delete');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Relationship not found';
        }
        return false;
    }

    /**
     * Get relationships on node
     * GET /node/123/relationships/{dir}/{-list|&|types}
     * 	Where dir is one of all,in,out and types is an ampersand-separated list of types. Some examples:
     * 	GET /node/123/relationships/out/KNOWS&LOVES -- outgoing relationships of types KNOWS and LOVES
     * 	GET /node/123/relationships/all/KNOWS -- relationships (both outgoing and incoming) of type LOVES
     * 	GET /node/123/relationships/in -- incoming relationships
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/node/123/relationships/out/KNOWS\&LOVES
     * 	Note: The ''&'' must be esaped in bash-like terminals (as included in the example, ''\'')
     * Response
     * 	200: OK
     * 	[
     * 		{
     * 			"self": "http://localhost:7474/db/data/relationship/56",
     * 			"start": "http://localhost:7474/db/data/node/123",
     * 			"end": "http://localhost:7474/db/data/node/93",
     * 			"type": "KNOWS",
     * 			"properties": "http://localhost:7474/db/data/relationship/56/properties",
     * 			"property": "http://localhost:7474/db/data/relationship/56/properties/{key}",
     * 			"data": { "date", 1270559208258 }
     * 		},
     * 		{
     * 			"self": "http://localhost:7474/db/data/relationship/834",
     * 			"start": "http://localhost:7474/db/data/node/32",
     * 			"end": "http://localhost:7474/db/data/node/123",
     * 			"type": "LOVES",
     * 			"properties": "http://localhost:7474/db/data/relationship/834/properties",
     * 			"property": "http://localhost:7474/db/data/relationship/834/properties/{key}",
     * 			"data": { "date", 1270559203821 }
     * 		}
     * 	]
     * 	404: Node not found
     * @param int $nid
     * @param string $direction $direction is one of all,in,out
     * @param int $expression {-list|&|types} Some example:KNOWS&LOVES,-list,types
     * @return mix
     */
    public function getRelationshipOnNode($nid, $direction = 'all', $expression = '') {
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/relationships/' . $direction . '/' . $expression, null, 'get');
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Node not found';
        }
        return false;
    }

    /**
     * Get relationships types
     * GET /relationship/types
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/relationship/types
     * Response
     * 	200: OK
     * 	["KNOWS","LOVES"]
     * @return mix
     */
    public function getRelationshipsTypes() {
        list($this->errorCode, $data) = $this->curlRequest('relationship/types');
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        }
        return false;
    }

    /**
     * Create an index, with configuration parameters
     * This is only necessary if you want to divert from the default index settings. If you are happy with defaults, you can just start indexing nodes and relationships, as non-existant indexes will automatically be created as you do.
     * POST /index/node
     * 	{
     * 		"name":"A unique index name",
     * 		"config":{
     * 			"provider":"An index provider", // For instance, "lucene", which is the default
     * 			// Other provider-specific configuration can be provided here
     * 			"type" : "fulltext" // Lucene-specific, use to create a fulltext index.
     * 		}
     * 	}
     * 	POST /index/relationship
     * 	{
     * 		"name":"A unique index name",
     * 		"config":{
     * 			"provider":"An index provider", // For instance, "lucene", which is the default
     * 			// Other provider-specific configuration can be provided here
     * 			"type" : "fulltext" // Lucene-specific, use to create a fulltext index.
     * 		}
     * 	}
     * Example using curl
     * 	curl -X POST -H Accept:application/json -HContent-Type:application/json -d '{"name":"fulltext", "config":{"type":"fulltext","provider":"lucene"}}' http://localhost:7474/db/data/index/node
     * Response
     * 	201: OK, the index was created (or was already created)
     * 	{
     * 		"template" : "http://localhost:7474/db/data/index/node/testing-fulltext/{key}/{value}",
     * 		"provider" : "lucene",
     * 		"type" : "fulltext"
     * 	}
     * @param string $type $type is one of node,relationship
     * @param array $data
     * @return mix
     */
    public function createIndex($type, $data) {
        list($this->errorCode, $data) = $this->curlRequest('index/' . $type, $data, 'post');
        if ($this->errorCode == 201) {
            $this->errorMsg = 'OK, the index was created (or was already created)';
            return $data;
        }
        return false;
    }

    /**
     * Deleting an index
     * DELETE /index/node/index_name
     * DELETE /index/relationship/index_name
     * Example using curl
     * 	curl -X DELETE http://localhost:7474/db/data/index/node/index_name
     * Response
     * 	204: OK, No content returned
     * @param string $type $type is one of node,relationship
     * @param string $indexName
     * @return boolean
     */
    public function deleteIndex($type, $indexName) {
        list($this->errorCode, $data) = $this->curlRequest('index/' . $type . '/' . $indexName, null, 'delete');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, No content returned';
            return true;
        }
        return false;
    }

    /**
     * Listing node indexes
     * GET /index/node
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/index/node
     * Response
     * 	200: OK
     * 	{
     * 		"my_nodes" : {
     * 		"template" : "http://localhost:7474/db/data/index/node/my_nodes/{key}/{value}",
     * 		"provider" : "lucene",
     * 		"type" : "exact"
     * 	}
     * @return mix
     */
    public function listingNodeIndexes() {
        list($this->errorCode, $data) = $this->curlRequest('index/node', null, 'get');
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        }
        return false;
    }

    /**
     * Listing relationship indexes
     * GET /index/relationship
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/index/relationship
     * Response
     * 	200: OK
     * 	{
     * 		"my_relationships" : {
     * 		"template" : "http://localhost:7474/db/data/index/relationship/my_relationships/{key}/{value}",
     * 		"provider" : "lucene",
     * 		"type" : "exact"
     * 	}
     * @return mix
     */
    public function listingRelationshipIndexes() {
        list($this->errorCode, $data) = $this->curlRequest('index/relationship');
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        }
        return false;
    }

    /**
     * Index a node or a relationship
     * Note: If the index you are adding a node or relationship to does not exist, one will be created with default configuration.
     * What "default" configuration means depends on how you have configured your database, if you haven't changed any indexing configuration, it means using Lucene.
     * If you want to divert from the default index settings, see "Create an index" above.
     * Also: This does *not* overwrite previous entries. If you index the same key/value/item combination twice, two index entries are created. To do update-type operations, you need to delete the old entry before adding a new one.
     * POST /index/node/my_nodes/the_key/the_value
     * 	Associates a node with the given key/value pair in the given index.
     * 	"http://localhost:7474/db/data/node/123"
     * Example using curl
     * 	curl -HContent-Type:application/json -X POST -d '"http://localhost:7474/db/data/node/123"' http://localhost:7474/db/data/index/node/my_nodes/the_key/the_value%20with%20space
     * Response
     * 	201: OK, indexed the node with key/value
     * 	Location: http://localhost:7474/db/data/index/node/my_nodes/the_key/the_value%20with%20space/123
     * 	POST /index/relationship/my_relationships/the_key/the_value
     * 	Associates a relationship with the given key/value pair in the given index.
     * 	"http://localhost:7474/db/data/relationship/456"
     * Example using curl
     * 	curl -HContent-Type:application/json -X POST -d '"http://localhost:7474/db/data/relationship/123"' http://localhost:7474/db/data/index/relationship/my_relationships/the_key/the_value
     * Response
     * 	201: OK, indexed the node with key/value
     * 	Location: http://localhost:7474/db/data/index/relationships/my_relationships/the_key/the_value/456
     */
    public function indexNodeOrRelationship() {

    }

    /**
     * Remove items from an index
     * The URIs used here can be found either in the response to a query to an index, where URIs like this will be included in each node/relationship representation returned. They are also returned (as Location in response header) when adding to an index.
     * DELETE /index/node/my_nodes/the_key/the_value/123
     * Will remove the index entry for node 123 with the specified key and value.
     * DELETE /index/relationship/my_relationship/the_key/the_value/123
     * Will remove the index entry for relationship 123 with the specified key and value.
     * Example using curl
     * 	curl -X DELETE http://localhost:7474/db/data/index/node/my_nodes/the_key/the_value/123
     * Response
     * 	204: OK, no content returned
     * 	404: Index entry not found
     * @return boolean
     */
    public function removeItemsFromIndex($type = 'node', $key, $value, $id) {
        list($this->errorCode, $data) = $this->curlRequest('index/' . $type . '/my_nodes/' . $key . '/' . $value . '/' . $id);
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Index entry not found';
        }
        return false;
    }

    /**
     * Remove items from an index without supplying value
     * DELETE /index/node/my_nodes/the_key/123
     * Will remove all indexing for node 123 and the specified key, regardless of value.
     * DELETE /index/relationship/my_relationships/the_key/123
     * Will remove all indexing for relationship 123 and the specified key, regardless of value.
     * Example using curl
     * 	curl -X DELETE http://localhost:7474/db/data/index/node/my_nodes/the_key/123
     * Response
     * 	204: OK, no content returned
     * @return boolean
     */
    public function removeItemsFromIndexWithoutSupplyingValue($type, $key, $id) {
        $myWhat = '';
        switch ($type) {
            case 'node':$myWhat = 'my_nodes';
                break;
            case 'relationship':$myWhat = 'my_relationships';
                break;
            default:break;
        }
        list($this->errorCode, $data) = $this->curlRequest('index/' . $type . '/' . $myWhat . '/' . $key . '/' . $id, null, 'delete');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        }
        return false;
    }

    /**
     * Remove items from an from index completely
     * DELETE /index/node/my_nodes/123
     * Will remove all mentions of node 123 in the entire index.
     * DELETE /index/relationship/my_relationships/123
     * 	Will remove all mentions of relationship 123 in the entire index.
     * Example using curl
     * 	curl -X DELETE http://localhost:7474/db/data/index/node/my_nodes/123
     * Response
     * 	204: OK, no content returned
     * @return boolean
     */
    public function removeItemsFromIndexCompletely($type, $id) {
        $myWhat = '';
        switch ($type) {
            case 'node':$myWhat = 'my_nodes';
                break;
            case 'relationship':$myWhat = 'my_relationships';
                break;
            default:break;
        }
        list($this->errorCode, $data) = $this->curlRequest('index/' . $type . '/' . $myWhat . '/' . $id, null, 'delete');
        if ($this->errorCode == 204) {
            $this->errorMsg = 'OK, no content returned';
            return true;
        }
        return false;
    }

    /**
     * Index search - Exact key/value lookup
     * Please note: Searching in indexes is only one of the two primary ways to search your data in neo4j, for advanced graph-based queries, see the traversal API, listed below.
     * There are two ways to use your indexes - exact key/value lookup, or using a query language.
     * This is the simple, exact lookup operation.
     * GET /index/node/my_nodes/the_key/the_value
     * GET /index/relationship/my_rels/the_key/the_value
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/index/node/my_nodes/the_key/the_value%20with%20space
     * 	curl -H Accept:application/json http://localhost:7474/db/data/index/relationship/my_rels/the_key/the_value%20with%20space
     * Response
     * 	200: OK
     * 	[
     * 		{
     * 			"self": "http://localhost:7474/db/data/relationship/56",
     * 			"start": "http://localhost:7474/db/data/node/123",
     * 			"end": "http://localhost:7474/db/data/node/93",
     * 			"type": "KNOWS",
     * 			"properties": "http://localhost:7474/db/data/relationship/56/properties",
     * 			"property": "http://localhost:7474/db/data/relationship/56/properties/{key}",
     * 			"data": { "date", 1270559208258 },
     * 			"indexed": "http://localhost:7474/db/data/index/relationship/the_key/the_value%20with%20space/56"
     * 		},
     * 		{
     * 			"self": "http://localhost:7474/db/data/relationship/834",
     * 			"start": "http://localhost:7474/db/data/node/32",
     * 			"end": "http://localhost:7474/db/data/node/123",
     * 			"type": "LOVES",
     * 			"properties": "http://localhost:7474/db/data/relationship/834/properties",
     * 			"property": "http://localhost:7474/db/data/relationship/834/properties/{key}",
     * 			"data": { "date", 1270559203821 },
     * 			"indexed": "http://localhost:7474/db/data/index/relationship/the_key/the_value/834"
     * 		}
     * 	]
     * 	The response is an ordered list of node or relationship representations, with one new property added: indexed. This property can be used to remove the indexed entity at a later point.
     * @return mix
     */
    public function indexSearch($query, $isKey, $type) {
        $myWhat = '';
        $tempStr = $isKey ? 'the_key/' : '';
        switch ($type) {
            case 'node':$myWhat = 'my_nodes';
                break;
            case 'relationship':$myWhat = 'my_rels';
                break;
            default:break;
        }
        list($this->errorCode, $data) = $this->curlRequest('index/' . $type . '/' . $myWhat . '/' . $tempStr . 'the_value/' . $query);
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        }
        return false;
    }

    /**
     * Index search - Using a query language
     * Please note: Searching in indexes is only one of the two primary ways to search your data in neo4j, for advanced graph-based queries, see the traversal API, listed below.
     * This is the advanced query operation for an index. The query syntax used here depends on what index provider you chose when you created the index.
     * The most common provider is Lucene, in which case you will want to use the Lucene query language, documented here:
     * http://lucene.apache.org/java/3_1_0/queryparsersyntax.html
     * If you are using another index provider, please refer to the documentation it provides for its #query(String query) method.
     * The examples below are using the Lucene query syntax.
     * GET /index/node/my_nodes?query=the_key:the_* AND the_other_key:[1 TO 100]
     * GET /index/relationship/my_rels?query=the_key:the_* AND the_other_key:[1 TO 100]
     * Example using curl
     * 	curl -H Accept:application/json http://localhost:7474/db/data/index/node/my_nodes?query=the_key:the_%2A%20AND%20the_other_key%3A%5B1%20TO%20100%5D
     * 	curl -H Accept:application/json http://localhost:7474/db/data/index/relationship/my_rels?query=the_key:the_%2A%20AND%20the_other_key%3A%5B1%20TO%20100%5D
     * Response
     * 	200: OK
     * 	[
     * 		{
     * 			"self": "http://localhost:7474/db/data/relationship/56",
     * 			"start": "http://localhost:7474/db/data/node/123",
     * 			"end": "http://localhost:7474/db/data/node/93",
     * 			"type": "KNOWS",
     * 			"properties": "http://localhost:7474/db/data/relationship/56/properties",
     * 			"property": "http://localhost:7474/db/data/relationship/56/properties/{key}",
     * 			"data": { "date", 1270559208258 },
     * 			"indexed": "http://localhost:7474/db/data/index/relationship/the_key/the_value%20with%20space/56"
     * 		},
     * 		{
     * 			"self": "http://localhost:7474/db/data/relationship/834",
     * 			"start": "http://localhost:7474/db/data/node/32",
     * 			"end": "http://localhost:7474/db/data/node/123",
     * 			"type": "LOVES",
     * 			"properties": "http://localhost:7474/db/data/relationship/834/properties",
     * 			"property": "http://localhost:7474/db/data/relationship/834/properties/{key}",
     * 			"data": { "date", 1270559203821 },
     * 			"indexed": "http://localhost:7474/db/data/index/relationship/the_key/the_value/834"
     * 		}
     * 	]
     * The response is an ordered list of node or relationship representations.
     * @return mix
     */
    public function indexSearch2($type, $query) {
        $myWhat = '';
        switch ($type) {
            case 'node':$myWhat = 'my_nodes';
                break;
            case 'relationship':$myWhat = 'my_rels';
                break;
            default:break;
        }
        list($this->errorCode, $data) = $this->curlRequest('index/' . $type . '/' . $myWhat . '?query=the_key:' . $query);
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        }
        return false;
    }

    /**
     * Traverse
     * POST /node/123/traverse/{returnType}
     * 	Where returnType is one if node, relationship, path, fullpath and specifies which kind of objects to return in the response.
     * 	The position object in the body of the prune evaluator is a org.neo4j.graphdb.Path object representing the path from the start node to the current traversal position.
     * 	{
     * 		"order": "depth_first",
     * 		"uniqueness": "node_path",
     * 		"relationships": [
     * 			{ "type": "KNOWS", "direction": "out" },
     * 			{ "type": "LOVES" }
     * 		],
     * 		"prune_evaluator": {
     * 			"language": "javascript",
     * 			"body": "position.endNode().getProperty('date')>1234567;"
     * 		},
     * 		"return_filter": {
     * 			"language": "builtin",
     * 			"name": "all"
     * 		},
     * 		"max_depth": 2
     * 	}
     * 	"max depth" is a short-hand way of specifying a prune evaluator which prunes after a certain depth. If not specified a max depth of 1 is used and if a "prune evaluator" is specified instead of a max depth, no max depth limit is set.
     * 	Builtin prune evaluators: none
     * 	Builtin return filters: all, all_but_start_node
     * 	Example using curl
     * 	curl -H Accept:application/json -H Content-Type:application/json -X POST -d '{"order":"depth_first"}' http://localhost:7474/db/data/node/123/traverse/node
     * Response
     * 	200: OK
     * 	If returnType=node:
     * 	[
     * 		{
     * 			"self": "http://localhost:7474/db/data/node/64",
     * 			"data": { "name": "Thomas Anderson" },
     * 			...
     * 		},
     * 		{
     * 			"self": "http://localhost:7474/db/data/node/635",
     * 			"data": { "name": "Agent Smith" },
     * 			...
     * 		}
     * 	]
     * 	If returnType=relationship:
     * 	[
     * 		{
     * 			"self": "http://localhost:7474/db/data/relationship/48",
     * 			"data": { "date", 1270559208258 },
     * 			...
     * 		},
     * 		{
     * 			"self": "http://localhost:7474/db/data/relationship/75",
     * 			"data": { "date", 1270559209483 },
     * 			...
     * 		}
     * 	]
     * 	If returnType=path:
     * 	[
     * 		{
     * 			"nodes": [
     * 				"http://localhost:7474/db/data/node/2",
     * 				"http://localhost:7474/db/data/node/351",
     * 				"http://localhost:7474/db/data/node/64"
     * 			],
     * 			"relationships": [
     * 				"http://localhost:7474/db/data/relationship/5",
     * 				"http://localhost:7474/db/data/relationship/48"
     * 			],
     * 			"start": "http://localhost:7474/db/data/node/2",
     * 			"end": "http://localhost:7474/db/data/node/64",
     * 			"length": 3
     * 		},
     * 		{
     * 			"nodes": [
     * 				"http://localhost:7474/db/data/node/2",
     * 				"http://localhost:7474/db/data/node/351",
     * 				"http://localhost:7474/db/data/node/635"
     * 			],
     * 			"relationships": [
     * 				"http://localhost:7474/db/data/relationship/5",
     * 				"http://localhost:7474/db/data/relationship/75"
     * 			],
     * 			"start": "http://localhost:7474/db/data/node/2",
     * 			"end": "http://localhost:7474/db/data/node/635",
     * 			"length": 3
     * 		},
     * 	]
     * 	If returnType=fullpath:
     * 	[
     * 		{
     * 			"nodes": [
     * 				{
     * 					"self": "http://localhost:7474/db/data/node/64",
     * 					"data": { "name": "Thomas Anderson" },
     * 					...
     * 				},
     * 				{
     * 					"self": "http://localhost:7474/db/data/node/635",
     * 					"data": { "name": "Agent Smith" },
     * 					...
     * 				}
     * 			],
     * 			"relationships": [
     * 				{
     * 					"self": "http://localhost:7474/db/data/relationship/48",
     * 					"data": { "date", 1270559208258 },
     * 					...
     * 				},
     * 				{
     * 					"self": "http://localhost:7474/db/data/relationship/75",
     * 					"data": { "date", 1270559209483 },
     * 					...
     * 				}
     * 			],
     * 			"start": {
     * 				"self": "http://localhost:7474/db/data/node/64",
     * 				"data": { "name": "Thomas Anderson" },
     * 				...
     * 			},
     * 			"end": {
     * 				"self": "http://localhost:7474/db/data/node/635",
     * 				"data": { "name": "Agent Smith" },
     * 				...
     * 			},
     * 			"length": 3
     * 		},
     * 		{
     * 			"nodes": [
     * 				....
     * 			"length": 3
     * 		}
     * 	]
     * 	404: Node not found
     * @param int $nid
     * @param string $returnType
     * 		node
     * 		relationship
     * 		path
     * 		fullpath
     * @param string $order
     * 		breadth_first Breadth-First Search 广度优先
     * 		depth_first Depth_First Search 深度优先
     * @param string $uniqueness
     * 		node_global A node cannot be traversed more than once.
     * 		none_path For each returned node there's a unique path from the start node to it.
     * 		node_recent This is like NODE_GLOBAL, but only guarantees uniqueness among the most recent visited nodes, with a configurable count.
     * 		none No restriction (the user will have to manage it).
     * 		relationship_global A relationship cannot be traversed more than once, whereas nodes can.
     * 		relationship_path For each returned node there's a (relationship wise) unique path from the start node to it.
     * 		realationship_recent Same as for NODE_RECENT, but for relationships.
     * @param array $relationships [{ "type": "KNOWS", "direction": "out" },{ "type": "LOVES" }]设置关系的类型和方向，方向包括all,in,out
     * @param array $pruneEvaluator {"language": "javascript","body": "position.endNode().getProperty('date')>1234567;"}
     * @param array $returnFilter {"language": "builtin","name": "all"},  all,all_but_start_node
     * @param int $maxDepth 2
     * @return mix
     */
    public function traverse($nid, $returnType = 'node', $order = 'depth_first', $uniqueness = 'node_global', $relationships = null, $pruneEvaluator = null, $returnFilter = null, $maxDepth = 5) {
        $postData = array();
        //$pruneEvaluator = array(
        //'body'=>'position.length()',
        //'name'=>'INCLUDE_AND_PRUNE',
        //'language'=>'builtin'
        //);
        $postData['order'] = $order;
        $uniqueness && $postData['uniqueness'] = $uniqueness;
        $relationships && $postData['relationships'] = $relationships;
        $pruneEvaluator && $postData['prune_evaluator'] = $pruneEvaluator;
        $returnFilter && $postData['return_filter'] = $returnFilter;
        $maxDepth && $postData['max_depth'] = $maxDepth;
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/paged/traverse/node?pageSize=4', $postData, 'post');
        //list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/paged/traverse/node?leaseTime=1', $postData, 'post');
        //list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/paged/traverse/node', $postData, 'post');
        //list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/traverse/' . $returnType, $postData, 'post');
        var_dump($data);
        if ($this->errorCode == 200 || $this->errorCode == 201) {
            return $data;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'Node not found';
        }
        return false;
    }

    /**
     * Finding a path between two nodes
     * POST /node/123/path
     * 	{
     * 		"to": "http://localhost:7474/db/data/node/456",
     * 		"relationships": {"type": "KNOWS", "direction": "out"},
     * 		"max_depth": 3,
     * 		"algorithm", "shortestPath"
     * 	}
     * The "algorithm" parameter should match the name of the corresponding method in GraphAlgoFactory. Currently supported algos are: shortestPath, allPaths, allSimplePaths and dijkstra.
     * Example using curl
     * 	curl -H Accept:application/json -H Content-Type:application/json -X POST http://localhost:7474/db/data/node/123/path -d '{"to":"http://localhost:7474/db/data/node/456","relationships":{"type":"KNOWS"},"algorithm":"shortestPath","max_depth":10}'
     * 	curl -H Accept:application/json -H Content-Type:application/json -X POST http://localhost:7474/db/data/node/123/path -d '{"to":"http://localhost:7474/db/data/node/456","relationships":{"type":"KNOWS"},"algorithm":"dijkstra","cost_property":"cost","default_cost":1}'
     * Response
     * 	200
     * 	{
     * 		"start" : "http://localhost:7474/db/data/node/123",
     * 		"nodes" : [ "http://localhost:7474/db/data/node/123", "http://localhost:7474/db/data/node/341", "http://localhost:7474/db/data/node/456" ],
     * 		"length" : 2,
     * 		"relationships" : [ "http://localhost:7474/db/data/relationship/564", "http://localhost:7474/db/data/relationship/32" ],
     * 		"end" : "http://localhost:7474/db/data/node/456"
     * 	}
     * 	Results from "dijkstra" algorithm will also have "weight".
     * 	404: No path found using current algorithm and parameters
     * @param int $nid
     * @param int $toUid
     * @param array $relationships
     * @param int $maxDepth
     * @param string $algorithm
     * @return mix
     */
    public function findingPathBetweenTwoNodes($nid, $toUid, $relationships, $maxDepth = 3, $algorithm = 'dijkstra') {
        $postData = array();
        $postData['to'] = $this->neo4jRest . 'node/' . $toUid;
        $postData['relationships'] = $relationships;
        $postData['max_depth'] = $maxDepth;
        $postData['algorithm'] = $algorithm;
        if ($algorithm == 'dijkstra') {
            $postData['cost_property'] = 'cost';
            $postData['default_cost'] = 1;
        }
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/path', $postData, 'post');
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        } elseif ($this->errorCode == 404) {
            $this->errorMsg = 'No path found using current algorithm and parameters';
        }
        return false;
    }

    /**
     * 找出两个节点之间的路径
     * Finding paths between two nodes
     * POST /node/123/paths
     * 	{
     * 		"to": "http://localhost:7474/db/data/node/456",
     * 		"relationships": {"type": "KNOWS", "direction": "out"},
     * 		"max_depth": 3,
     * 		"algorithm":"shortestPath"
     * 	}
     * The "algorithm" parameter should match the name of the corresponding method in GraphAlgoFactory. Currently supported algos are: shortestPath, allPaths, allSimplePaths and dijkstra. More will be supported later on.
     * Example using curl
     * 	curl -H Accept:application/json -H Content-Type:application/json -X POST http://localhost:7474/db/data/node/123/paths -d '{"to":"http://localhost:7474/db/data/node/456","relationships":{"type":"KNOWS"},"algorithm":"shortestPath","max_depth":10}'
     * 	curl -H Accept:application/json -H Content-Type:application/json -X POST http://localhost:7474/db/data/node/123/paths -d '{"to":"http://localhost:7474/db/data/node/456","relationships":{"type":"KNOWS"},"algorithm":"dijkstra","cost_property":"cost","default_cost":1}'
     * Response
     * 	200
     * 	[
     * 		{
     * 			"start" : "http://localhost:7474/db/data/node/123",
     * 			"nodes" : [ "http://localhost:7474/db/data/node/123", "http://localhost:7474/db/data/node/341", "http://localhost:7474/db/data/node/456" ],
     * 			"length" : 2,
     * 			"relationships" : [ "http://localhost:7474/db/data/relationship/564", "http://localhost:7474/db/data/relationship/32" ],
     * 			"end" : "http://localhost:7474/db/data/node/456"
     * 		}, {
     * 			"start" : "http://localhost:7474/db/data/node/123",
     * 			"nodes" : [ "http://localhost:7474/db/data/node/123", "http://localhost:7474/db/data/node/41", "http://localhost:7474/db/data/node/456" ],
     * 			"length" : 2,
     * 			"relationships" : [ "http://localhost:7474/db/data/relationship/437", "http://localhost:7474/db/data/relationship/97" ],
     * 			"end" : "http://localhost:7474/db/data/node/456"
     * 		}
     * 	]
     * 	Results from "dijkstra" algorithm will also have "weight".
     * 	204: No path found using current algorithm and parameters
     * @param int $nid
     * @param int $toUid
     * @param array $relationships
     * @param int $maxDepth
     * @param string $algorithm
     * @return mix
     */
    public function findingPathsBetweenTwoNodes($nid, $toUid, $relationships, $maxDepth = 3, $algorithm = 'shortestPath') {
        $postData = array();
        $postData['to'] = $this->neo4jRest . 'node/' . $toUid;
        $postData['relationships'] = $relationships;
        $postData['max_depth'] = $maxDepth;
        $postData['algorithm'] = $algorithm;
        if ($algorithm == 'dijkstra') {
            $postData['cost_property'] = 'cost';
            $postData['default_cost'] = 1;
        }
        list($this->errorCode, $data) = $this->curlRequest('node/' . $nid . '/paths', $postData, 'post');
        if ($this->errorCode == 200) {
            return $data;
        } elseif ($this->errorCode == 204) {
            $this->errorMsg = 'No path found using current algorithm and parameters';
        }
        return false;
    }

    /**
     * Cypher language query
     * @param string $queryString
     * @param array $params
     */
    public function cypherQuery($queryString, $params = array()) {
        $postData = array();
        $postData['query'] = $queryString;
        $postData['params'] = new Object();
        list($this->errorCode, $data) = $this->curlRequest('cypher', $postData, 'post');
        if ($this->errorCode == 200) {
            $this->errorMsg = 'OK';
            return $data;
        } elseif ($this->errorCode == 400) {
            $this->errorMsg = 'Bad Request';
        }
        return false;
    }

    /**
     * Gremlinlanguage query
     * @param string $queryString
     */
    public function gremlinQuery($queryString) {
        $postData = array('script' => $queryString);
        $postData = array('script' => 'g.v(1).out.name.paths');
        list($this->errorCode, $data) = $this->curlRequest('ext/GremlinPlugin/graphdb/execute_script', $postData, 'post');
        if ($this->errorCode == 200) {
            return $data;
        } elseif ($this->errorCode == 400) {
            $this->errorMsg = 'Bad Request';
        }
        return false;
    }

    /**
     * 发送curl请求
     * @param string $shorUrl
     * @param array $postData
     * @param string $methord put,post,null
     * @return array
     */
    private function curlRequest($shorUrl = '', $postData = null, $method = null) {
        curl_setopt($this->ch, CURLOPT_URL, $this->neo4jRest . $shorUrl);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($postData) {
            $postData = json_encode($postData);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postData);
            $headers = array(
                'Content-Length: ' . strlen($postData),
                'Content-Type: application/json',
                'Accept: application/json'
            );
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($this->ch);
        $responseCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        return array($responseCode, json_decode($response));
    }

    /**
     * 关闭curl和缓存文件
     */
    public function __destruct() {
        curl_close($this->ch);
    }

}

class object {

}