<?php
/**
 * The Update Query builder Class.
 *
 * @author RN Kushwaha <rn.kushwaha022@gmail.com>
 * @since v0.0.8 <Date: 09th May, 2019>
 */

namespace Dolphin\Builders;

use Dolphin\Connections\Connection;
use Exception;

/**
 * This class provides the mechanism to build the Insert Queries.
 */
class UpdateQueryBuilder extends QueryBuilder
{
    /**
     * It inserts the new rows
     *
     * @param array $rows
     * @return integer $lastInsertedId
     * @throws Exception
     * @author RN Kushwaha <rn.kushwaha022@gmail.com>
     * @since v0.0.5
     */
    public function update($rows, $table)
    {
        $db = Connection::get();
        $wqb   = new WhereQueryBuilder();
        $query = "UPDATE ".$table." SET ";
        $ar    = array();

        foreach($row as $key => $val){
            $ar[':'.$key] = $val;
            $query.= $this->quote($key)." =:".$key.",";
        }

        $query = rtrim($query, ",");

        try{
            $whereQuery = $wqb->buildAllWhereQuery(
                                $this->where,
                                $this->whereRaw,
                                $this->whereIn,
                                $this->whereNotIn,
                                $this->whereNull,
                                $this->whereNotNull
                            );
            $query.= " ".join(" ", $whereQuery);
            $stmt = Connection::get()->prepare($this->queryPrefix($query));
            $stmt->execute($ar);
            $this->reset();
        } catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}