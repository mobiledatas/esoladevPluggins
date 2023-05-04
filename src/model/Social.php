<?php

namespace Model;

use Entity\Social as EntitySocial;
use FaaPz\PDO\Clause\Conditional;
use FaaPz\PDO\Statement\Select;
use FaaPz\PDO\Clause\Join;
use FaaPz\PDO\Clause\Raw;
use Lib\Database;

class Social
{

    private $tablename = 'esola_social_post';

    public function get($id)
    {
        global $wpdb;
        $pdo = (new Database())->simplePdo();
        $select = $pdo->select()->from($wpdb->prefix . $this->tablename)->where(new Conditional('id', '=', $id));
        $result = $select->execute();
        return $result->fetchAll();
    }

    public function create(EntitySocial $social)
    {
        global $wpdb;
        $pdo = (new Database())->simplePdo();
        $insert = $pdo->insert([
            'icon',
            'name',
            'link',
            'post_id',
            'lang'
        ])
            ->into($wpdb->prefix . $this->tablename)
            ->values(
                $social->icon,
                $social->name,
                $social->link,
                $social->post_id,
                $social->lang
            );
        $res = $insert->execute();
        if ($res != false) {
            return $pdo->lastInsertId();
        }
    }

    public function update(EntitySocial $social)
    {
        global $wpdb;
        $pdo = (new Database())->simplePdo();
        $update = $pdo->update([
            'name' => $social->name,
            'link' => $social->link,
            'post_id' => $social->post_id,
            'lang' => $social->lang
        ])->table($wpdb->prefix . $this->tablename)
            ->where(new Conditional('id', '=', $social->id));
        $res = $update->execute();
        if ($res !== false) {
            return $res->rowCount();
        }
        return $res;
    }

    public function delete(EntitySocial $social)
    {
        global $wpdb;
        $pdo = (new Database())->connect();
        $table  = $wpdb->prefix . $this->tablename;
        $stmt = $pdo->prepare("DELETE FROM `$table` WHERE id = :id ");
        $stmt->bindValue(':id', $social->id);
        $stmt->execute();
        $result = $stmt->rowCount();
        return $result;
    }

    public function all()
    {
        global $wpdb;
        $pdo = (new Database())->simplePdo();
        $select = $pdo->select()->from($wpdb->prefix . $this->tablename)->where(new Conditional('lang', '=', pll_current_language() != null ? pll_current_language() : 'es'))->join(new Join($wpdb->prefix . 'posts', new Conditional($wpdb->prefix . $this->tablename . '.post_id', '=', new Raw($wpdb->prefix . 'posts.id'))));
        $result = $select->execute();
        return $result->fetchAll();
    }
}
