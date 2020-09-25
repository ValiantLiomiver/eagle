<?php
/*
 * EAGLE DB
 * v 1.2
 * date: 09/04/2013
 * last update: 12/10/2015
 * made by: Andrisani Umberto
 * copyright: Andrisani Umberto 2013
 * 
 * */

class Eagle_db{
	private $db = array('type'=>null,'res'=>null);
	private $parts = array(
							'select'=>array(),
							'from'=>array(),
							'join'=>array(),
							'left_join'=>array(),
							'right_join'=>array(),
							'inner_join'=>array(),
							'natural_join'=>array(),
							'where'=>array(),
							'group_by'=>array(),
							'having'=>array(),
							'order_by'=>array(),
							'limit'=>null,
							'offset'=>null
						);
	
	//function __construct($dbtype='mysql',$dbname,$dbhostname,$dbuser,$dbpassword){
	function __construct($param){
		if(is_string($param)){
			if(preg_match("/^(\w*):dbname=(\w*);host=(\w*);user=(\w*);password=(\w*)$/",$param)){
				list($dbtype,$dbname,$dbhostname,$dbuser,$dbpassword) = explode(';',preg_replace("/^(\w*):dbname=(\w*);host=(\w*);user=(\w*);password=(\w*)$/",'$1;$2;$3;$4;$5',$param));
				$dsn = $dbtype.":dbname=".$dbname.";host=".$dbhostname;
				$this->db['type'] = $dbtype;
				$this->db['res'] = New PDO($dsn, $dbuser, $dbpassword);
			}
		}
		elseif(is_array($param)){
			if(array_key_exists('dbtype',$param) && array_key_exists('dbname',$param) && array_key_exists('dbhostname',$param) && array_key_exists('dbuser',$param) && array_key_exists('dbpassword',$param)){
				foreach($param as $k=>$v) ${$k} = $v;
				$dsn = $dbtype.":dbname=".$dbname.";host=".$dbhostname;
				$this->db['type'] = $dbtype;
				$this->db['res'] = New PDO($dsn, $dbuser, $dbpassword);
			}
		}
	}
	
	function __destruct(){
		
	}
	
	private function reset_parts(){
		$this->parts = array(
							'select'=>array(),
							'from'=>array(),
							'join'=>array(),
							'left_join'=>array(),
							'right_join'=>array(),
							'inner_join'=>array(),
							'natural_join'=>array(),
							'where'=>array(),
							'group_by'=>array(),
							'having'=>array(),
							'order_by'=>array(),
							'limit'=>null,
							'offset'=>null
						);
	}
	
	function GetType(){
		return $this->db['type'];
	}
	
	function GetField($field=0){
		$ret = false;
		$query = $this->get_query();
		if(isset($query) && $query){
			$this->reset_parts();
			$ret = $this->FieldReturn($query,$field);
		}
		return $ret;
	}
	
	function GetRow(){
		$ret = false;
		$query = $this->get_query();
		if(isset($query) && $query){
			$this->reset_parts();
			$ret = $this->RowReturn($query);
		}
		return $ret;
	}
	
	function Ins($query){
		$this->db['res']->exec($query) or die(print_r($this->db['res']->errorInfo(), true));
	}
	
	function Insert($table, $data){
		$ret = false;
		$app = $data;
		foreach($data as $k=>$v){
			if(isset($v) && $v) $app[$k] = "?";
			else $app[$k] = null;
		}
		$query = "INSERT INTO ".$table." (".implode(',', array_keys($app)).") VALUES (".implode(',', array_values($app)).")";
		$app = array();
		unset($app);
		$res = $this->db['res']->prepare($query);
		if($res->execute(array_values($data))) $ret = true;
		else $ret = false;
		return $ret;
	}
	
	function Exec($query, $params=null){
		$params = !is_null($params) && is_array($params) ? $params : array();
		$this->reset_parts();
		$res = $this->db['res']->prepare($query);
		if($res->execute($params)) return $res;
		else return false;
	}
	
	function Fetch($res){
		$ret = false;
		if($res) $ret = $res->fetch();
		
		return $ret;
	}
	
	function NumRows($res){
		return $res->rowCount();
	}
	
	function LastInsertId(){
		return $this->db['res']->lastInsertId();
	}
	
	function FieldReturn($query, $field=0, $params=null){
		$params = !is_null($params) && is_array($params) ? $params : array();
		$res = $this->Exec($query, $params);
		$ret = $this->Fetch($res);
		return $ret[$field];
	}
	
	function RowReturn($query, $params=null){
		$params = !is_null($params) && is_array($params) ? $params : array();
		$res = $this->Exec($query, $params);
		$ret = $this->Fetch($res);
		return $ret;
	}
	
	function Transaction(){
		$this->db['res']->beginTransaction();
	}
	
	function Commit(){
		$this->db['res']->commit();
	}
	
	function RollBack(){
		$this->db['res']->rollBack();
	}
	
	function Quote($string){
		return $this->db['res']->quote($string);
	}
	
	function select($fields){
		if(isset($fields) && $fields){
			$this->parts['select'][] = $fields;
		}
		return $this;
	}
	
	function from($from){
		if(isset($from) && $from){
			$this->parts['from'][] = $from;
		}
		return $this;
	}
	
	function join($join,$on=null){
		if(isset($join) && $join){
			$this->parts['join'][] = array('join'=>$join,'on'=>$on);
		}
		return $this;
	}
	
	function left_join($join,$on=null){
		if(isset($join) && $join){
			$this->parts['left_join'][] = array('join'=>$join,'on'=>$on);
		}
		return $this;
	}
	
	function right_join($join,$on=null){
		if(isset($join) && $join){
			$this->parts['right_join'][] = array('join'=>$join,'on'=>$on);
		}
		return $this;
	}
	
	function inner_join($join,$on=null){
		if(isset($join) && $join){
			$this->parts['inner_join'][] = array('join'=>$join,'on'=>$on);
		}
		return $this;
	}
	
	function natural_join($join,$on=null){
		if(isset($join) && $join){
			$this->parts['natural_join'][] = array('join'=>$join,'on'=>$on);
		}
		return $this;
	}
	
	function where($where){
		if(isset($where) && $where){
			$this->parts['where'][] = $where;
		}
		return $this;
	}
	
	function or_where($where){
		if(isset($where) && $where){
			if(count($this->parts['where'])){
				$last = array_pop($this->parts['where']);
				$this->parts['where'][] = "(".$last." OR ".$where.")";
			}
			else{
				$this->parts['where'][] = $where;
			}
		}
		return $this;
	}
	
	function and_where($where){
		if(isset($where) && $where){
			if(count($this->parts['where'])){
				$last = array_pop($this->parts['where']);
				$this->parts['where'][] = "(".$last." AND ".$where.")";
			}
			else{
				$this->parts['where'][] = $where;
			}
		}
		return $this;
	}
	
	function group_by($group){
		if(isset($group) && $group){
			$this->parts['group_by'][] = $group;
		}
		return $this;
	}
	
	function order_by($order_by,$method=null){
		if(isset($order_by) && $order_by){
			$this->parts['order_by'][] = $order_by.((isset($method) && $method)?(" ".$method):(''));
		}
		return $this;
	}
	
	function having($having){
		if(isset($having) && $having){
			$this->parts['having'][] = $having;
		}
		return $this;
	}
	
	function limit($limit){
		if(isset($limit) && $limit){
			$this->parts['limit'] = $limit;
		}
		return $this;
	}
	
	function offset($offset){
		if(isset($offset) && intval($offset)>=0){
			$this->parts['offset'] = intval($offset);
		}
		return $this;
	}
	
	function get($tablename,$orderby=null,$limit=null,$offset=null,$get_query=false){
		$this->reset_parts();
		$ret = null;
		if(isset($get_query) && $get_query) $ret = $this->select("*")->from($tablename)->order_by($orderby)->limit($limit)->offset($offset)->get_query();
		else $ret = $this->select("*")->from($tablename)->order_by($orderby)->limit($limit)->offset($offset)->Execute();
		return $ret;
	}
	
	function count($table_name,$field,$condition=null){
		$this->reset_parts();
		return $this->FieldReturn($this->Select("count(".$field.")")->From($table_name)->Where($condition)->Limit(1)->get_query());
	}
	
	function Execute($params=null){
		$ret = false;
		$params = !is_null($params) && is_array($params) ? $params : array();
		$query = $this->get_query();
		if(isset($query) && $query){
			$this->reset_parts();
			$ret = $this->Exec($query, $params);
		}
		return $ret;
	}
	
	function get_query(){
		$ret = null;
		if(isset($this->parts['select']) && count($this->parts['select'])){
			$pieces = array();
			$pieces[] = "SELECT ".implode(',',$this->parts['select'])." FROM ";
			if(isset($this->parts['from']) && count($this->parts['from'])){
				$pieces[] = implode(',',$this->parts['from']);
				if(isset($this->parts['join']) && count($this->parts['join'])){
					foreach($this->parts['join'] as $a){
						$pieces[] = "JOIN ".$a['join'].((isset($a['on']) && $a['on'])?(" ON (".$a['on'].")"):(''));
					}
				}
				if(isset($this->parts['left_join']) && count($this->parts['left_join'])){
					foreach($this->parts['left_join'] as $a){
						$pieces[] = "LEFT JOIN ".$a['join'].((isset($a['on']) && $a['on'])?(" ON (".$a['on'].")"):(''));
					}
				}
				if(isset($this->parts['right_join']) && count($this->parts['right_join'])){
					foreach($this->parts['right_join'] as $a){
						$pieces[] = "RIGHT JOIN ".$a['join'].((isset($a['on']) && $a['on'])?(" ON (".$a['on'].")"):(''));
					}
				}
				if(isset($this->parts['inner_join']) && count($this->parts['inner_join'])){
					foreach($this->parts['inner_join'] as $a){
						$pieces[] = "INNER JOIN ".$a['join'].((isset($a['on']) && $a['on'])?(" ON (".$a['on'].")"):(''));
					}
				}
				if(isset($this->parts['natural_join']) && count($this->parts['natural_join'])){
					foreach($this->parts['natural_join'] as $a){
						$pieces[] = "NATURAL JOIN ".$a['join'].((isset($a['on']) && $a['on'])?(" ON (".$a['on'].")"):(''));
					}
				}
				if(isset($this->parts['where']) && count($this->parts['where'])){
					$pieces[] = "WHERE ".implode(' AND ',$this->parts['where']);
				}
				if(isset($this->parts['group_by']) && count($this->parts['group_by'])){
					$pieces[] = "GROUP BY ".implode(',',$this->parts['group_by']);
				}
				if(isset($this->parts['having']) && count($this->parts['having'])){
					$pieces[] = "HAVING ".implode(' AND ',$this->parts['having']);
				}
				if(isset($this->parts['order_by']) && count($this->parts['order_by'])){
					$pieces[] = "ORDER BY ".implode(',',$this->parts['order_by']);
				}
				if(isset($this->parts['limit']) && $this->parts['limit']){
					$pieces[] = "LIMIT ".$this->parts['limit'];
				}
				if(isset($this->parts['offset']) && intval($this->parts['offset'])>=0){
					$pieces[] = "OFFSET ".$this->parts['offset'];
				}
				//var_dump(implode(' ',$pieces));
				$ret = implode(' ',$pieces);
			}
		}
		$this->reset_parts();
		return $ret;
	}
}
