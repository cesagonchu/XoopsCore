<?php
/**
  You may not change or alter any portion of this comment or credits
  of supporting developers from this source code or any supporting source code
  which is considered copyrighted (c) material of the original comment or credit authors.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Connection wrapper for Doctrine DBAL Connection
 *
 * PHP version 5.3
 *
 * @category  Xoops\Class\Database\QueryBuilder
 * @package   QueryBuilder
 * @author    readheadedrod <redheadedrod@hotmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   http://www.fsf.org/copyleft/gpl.html GNU public license
 * @version   Release: 2.6.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
class XoopsQueryBuilder extends \Doctrine\DBAL\Query\QueryBuilder
{

    /**
     * Turns the query being built into a bulk delete query that ranges over
     * a certain table.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->delete('users', 'u')
     *         ->where('u.id = :user_id');
     *         ->setParameter(':user_id', 1);
     * </code>
     *
     * @param string $delete The table whose rows are subject to the deletion.
     * Adds table prefix to table.
     * @param string $alias  The table alias used in the constructed query.
     *
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function deletePrefix($delete = null, $alias = null)
    {
        $delete = XoopsConnection::prefix($delete);
        return $this->delete($delete, $alias);
    }

    /**
     * Turns the query being built into a bulk update query that ranges over
     * a certain table
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->update('users', 'u')
     *         ->set('u.password', md5('password'))
     *         ->where('u.id = ?');
     * </code>
     *
     * @param string $update The table whose rows are subject to the update.
     * Adds table prefix to table.
     * @param string $alias  The table alias used in the constructed query.
     *
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function updatePrefix($update = null, $alias = null)
    {
        $update = XoopsConnection::prefix($update);
        return $this->update($update, $alias);
    }

    /**
     * Create and add a query root corresponding to the table identified by the
     * given alias, forming a cartesian product with any existing query roots.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.id')
     *         ->from('users', 'u')
     * </code>
     *
     * @param string $from  The table.
     * Adds table prefix to table.
     * @param string $alias The alias of the table
     *
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function fromPrefix($from, $alias)
    {
        $from = XoopsConnection::prefix($from);
        return $this->from($from, $alias);
    }

    /**
     * Creates and adds a join to the query.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->join('u', 'phonenumbers', 'p', 'p.is_primary = 1');
     * </code>
     *
     * @param string $fromAlias The alias that points to a from clause
     * @param string $join      The table name to join. Adds table prefix to table.
     * @param string $alias     The alias of the join table
     * @param string $condition The condition for the join
     *
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function joinPrefix($fromAlias, $join, $alias, $condition = null)
    {
        $join = XoopsConnection::prefix($join);
        return $this->join($fromAlias, $join, $alias, $condition);
    }


    /**
     * Creates and adds a join to the query.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->innerJoin('u', 'phonenumbers', 'p', 'p.is_primary = 1');
     * </code>
     *
     * @param string $fromAlias The alias that points to a from clause
     * @param string $join      The table name to join. Adds table prefix to table.
     * @param string $alias     The alias of the join table
     * @param string $condition The condition for the join
     *
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function innerJoinPrefix($fromAlias, $join, $alias, $condition = null)
    {
        $join = XoopsConnection::prefix($join);
        return $this->innerJoin($fromAlias, $join, $alias, $condition);
    }

    /**
     * Creates and adds a left join to the query.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->leftJoin('u', 'phonenumbers', 'p', 'p.is_primary = 1');
     * </code>
     *
     * @param string $fromAlias The alias that points to a from clause
     * @param string $join      The table name to join. Adds table prefix to table.
     * @param string $alias     The alias of the join table
     * @param string $condition The condition for the join
     *
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function leftJoinPrefix($fromAlias, $join, $alias, $condition = null)
    {
        $join = XoopsConnection::prefix($join);
        return $this->leftJoin($fromAlias, $join, $alias, $condition);
    }

    /**
     * Creates and adds a right join to the query.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->rightJoin('u', 'phonenumbers', 'p', 'p.is_primary = 1');
     * </code>
     *
     * @param string $fromAlias The alias that points to a from clause
     * @param string $join      The table name to join. Adds table prefix to table.
     * @param string $alias     The alias of the join table
     * @param string $condition The condition for the join
     *
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function rightJoinPrefix($fromAlias, $join, $alias, $condition = null)
    {
        $join = XoopsConnection::prefix($join);
        return $this->rightJoin($fromAlias, $join, $alias, $condition);
    }
}
