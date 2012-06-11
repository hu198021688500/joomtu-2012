<?php
/**
 * GraphDatabaseService abstracts a Neo4J database server.
 *
 * @package NeoRest
 */

/**
 * GraphDatabaseService abstracts a Neo4J database server.
 *
 * @example ../examples/demo.php Using the GraphDatabaseService
 *
 * @package NeoRest
 */
class GraphDatabaseService
{
	public $base_uri;
	
	/**
	 * JSON HTTP client
	 *
	 * @var JSONClient
	 */
	protected $jsonClient;
	
	public function __construct($base_uri, $jsonClient=null){
		$this->base_uri = $base_uri;
		if (!is_null($jsonClient)) {
		    $this->jsonClient = $jsonClient;
		} else {
		    $this->jsonClient = new JsonClient;
		}
	}
	
	public function getNodeById($id) {
	     return $this->getNodeByUri($this->base_uri.'node/'.$id);   
	}
	
	public function getNodeByUri($uri){
		list($response, $http_code) = $this->jsonClient->jsonGetRequest($uri);
		switch ($http_code){
			case 200:
				return Node::inflateFromResponse($this, $response);
			case 404:
				throw new NotFoundException();
			default:
				throw new NeoRestHttpException($http_code);
		}
	}
	
    public function getRelationshipById($id) {
	     return $this->getRelationshipByUri($this->base_uri.'relationship/'.$id);   
	}
	
	public function getRelationshipByUri($uri){
		list($response, $http_code) = $this->jsonClient->jsonGetRequest($uri);
		switch ($http_code){
			case 200:
				return Relationship::inflateFromResponse($this, $response);
			case 404:
				throw new NotFoundException();
			default:
				throw new NeoRestHttpException($http_code);
		}
	}
	
	public function createNode(){
		return new Node($this);
	}
	
	public function getBaseUri(){
		return $this->base_uri;
	}
	
	/**
	 * 批处理操作
	 * [{"method":"post","to":"\/node","body":{"account":"hu198021688500@163.com"},"id":0}] 
	 * Enter description here ...
	 * @param unknown_type $data
	 * @throws NotFoundException
	 * @throws NeoRestHttpException
	 */
	public function batchOperate($data){
		$uri = $this->base_uri.'batch';
		list($response, $http_code) = $this->jsonClient->jsonPostRequest($uri, $data);
		switch ($http_code){
			case 200:
				return $response;
			case 404:
				throw new NotFoundException();
			default:
				throw new NeoRestHttpException($http_code);
		}
	}
	
	/**
	 * Cypher Query
	 * start x  = (5) return x.dummy
	 * start x  = (7) match path = (x--friend) return path, friend.name
	 * @param string $query
	 * @param unknown_type $inflate_nodes
	 * @throws NotFoundException
	 * @throws HttpException
	 */
	public function performCypherQuery($query, $inflate_nodes=true){ 
		$uri = $this->base_uri.'ext/CypherPlugin/graphdb/execute_query';
		$data = array('query'=>$query);
		$this->query($uri, $data, $inflate_nodes);
		
	}
	
	/**
	 * Gremlin Query
	 * g.v(13).out.sort{it.name}.toList()
	 * Enter description here ...
	 * @param unknown_type $query
	 * @param unknown_type $inflate_nodes
	 */
	public function performGremlinQuery($query, $inflate_nodes=true){
		$uri = $this->base_uri.'ext/GremlinPlugin/graphdb/execute_script';
		$data = array('script'=>$query);
		$this->query($uri, $data, $inflate_nodes);
	}
	
	private function query($uri, $data, $inflate_nodes = true){
		list($response, $http_code) = $this->jsonClient->jsonPostRequest($uri, $data);
		if ($inflate_nodes && $http_code == 200) {
			for($i=0;$i<count($response['data']); $i++){
				for($j=0;$j<count($response['data'][$i]); $j++) {
					if (is_array($response['data'][$i][$j]) && isset($response['data'][$i][$j]['data'])) {
						$response['data'][$i][$j] = Node::inflateFromResponse($this, $response['data'][$i][$j]);
					}
				}
			}
		}
		switch ($http_code){
			case 200:
				return $response;
			case 404:
				throw new NotFoundException();
			default:
				throw new NeoRestHttpException($http_code);
		}
	}
}