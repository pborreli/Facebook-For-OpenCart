<?php
// Copyright 2017-present, Facebook, Inc.
// All rights reserved.

// This source code is licensed under the license found in the
// LICENSE file in the root directory of this source tree.

  // creates the facebook_product table
  $facebook_product_table_exists_sql = sprintf("SHOW TABLES IN `%s` " .
    "LIKE '%sfacebook_product'",
    DB_DATABASE,
    DB_PREFIX);
  $data = $this->db->query($facebook_product_table_exists_sql)->rows;
  // checks if the table exist
  if (sizeof($data) == 0) {
    $create_facebook_product_sql = sprintf("CREATE TABLE `%s`." .
      "`%sfacebook_product` (" .
      "`product_id` INT NOT NULL, " .
      "`facebook_product_id` BIGINT(20) NOT NULL, " .
      "PRIMARY KEY (`product_id`));",
      DB_DATABASE,
      DB_PREFIX);
    $this->db->query($create_facebook_product_sql);
  }

  // checks if product group id exists
  $facebook_product_group_col_exists_sql = sprintf("SHOW COLUMNS IN " .
    "`%s`.`%sfacebook_product` LIKE 'facebook_product_group_id'",
    DB_DATABASE,
    DB_PREFIX);
  $data = $this->db->query($facebook_product_group_col_exists_sql)->rows;
  if (sizeof($data) === 0) {
    $create_facebook_product_group_sql = sprintf("ALTER TABLE `%s`." .
      "`%sfacebook_product` ADD COLUMN " .
      "(`facebook_product_group_id` BIGINT(20) NOT NULL DEFAULT 0) ",
      DB_DATABASE,
      DB_PREFIX);
    $this->db->query($create_facebook_product_group_sql);
  }

  // adds Facebook Ads Extension access permission for Administrator
  $this->load->model('user/user_group');
  $user_groups = $this->model_user_user_group->getUserGroups();
  $admin_user_group_id = NULL;
  foreach ($user_groups as $user_group) {
    if ($user_group['name'] === 'Administrator') {
      $admin_user_group_id = $user_group['user_group_id'];
      break;
    }
  }
  if (!is_null($admin_user_group_id)) {
    $this->model_user_user_group->addPermission(
      $admin_user_group_id,
      "access",
      "facebook/facebookadsextension");
    $this->model_user_user_group->addPermission(
      $admin_user_group_id,
      "modify",
      "facebook/facebookadsextension");
  }
