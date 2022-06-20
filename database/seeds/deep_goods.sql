-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-06-20 12:49:13
-- 服务器版本： 5.7.30-log
-- PHP 版本： 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `nft`
--

-- --------------------------------------------------------

--
-- 表的结构 `brands`
--

CREATE TABLE `brands` (
                          `id` bigint(20) UNSIGNED NOT NULL,
                          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `index_name` char(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '索引首字母',
                          `source` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '产地国家',
                          `source_icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '产地图标',
                          `created_at` timestamp NULL DEFAULT NULL,
                          `updated_at` timestamp NULL DEFAULT NULL,
                          `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods`
--

CREATE TABLE `goods` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `user_id` int(11) NOT NULL COMMENT '用户ID',
                         `shop_id` int(11) DEFAULT '0' COMMENT '商家ID',
                         `goods_class_path` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `goods_class_id` int(11) NOT NULL COMMENT '分类ID',
                         `brand_id` int(11) DEFAULT '0',
                         `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品名称',
                         `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述',
                         `on_shelf` tinyint(1) NOT NULL DEFAULT '0' COMMENT '上架',
                         `one_attr` tinyint(1) DEFAULT NULL,
                         `price` decimal(11,2) DEFAULT '0.00',
                         `cost_price` decimal(11,2) DEFAULT '0.00',
                         `line_price` decimal(11,2) DEFAULT '0.00',
                         `stock_num` int(11) DEFAULT '0',
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         `deleted_at` timestamp NULL DEFAULT NULL,
                         `owner` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '拥有者用户名',
                         `warrant_address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '证券地址',
                         `work_id` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '作品ID',
                         `publish_count` int(11) DEFAULT '0' COMMENT '发行总数',
                         `number_views` int(11) DEFAULT '0' COMMENT '浏览次数',
                         `talent_id` int(11) DEFAULT '0' COMMENT '发行方',
                         `summary` text COLLATE utf8mb4_unicode_ci COMMENT '简介',
                         `type` int(11) DEFAULT '1' COMMENT '类型',
                         `work_desc` text COLLATE utf8mb4_unicode_ci COMMENT '藏品描述',
                         `work_copy` text COLLATE utf8mb4_unicode_ci COMMENT '藏品副本',
                         `tag_id` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '标签',
                         `image` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `on_shelf_time` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '自动上架时间',
                         `chain_status` int(11) DEFAULT '0' COMMENT '上链状态',
                         `asset_id` varchar(124) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '资产id',
                         `img_url_360` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片360',
                         `img_url_850` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片850',
                         `img_url_origin` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片原图',
                         `attr_key` int(11) DEFAULT '1' COMMENT '子藏品序号',
                         `rebate_price` decimal(9,2) DEFAULT '0.00' COMMENT '返佣价格',
                         `show_app` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '展示app'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_attrs`
--

CREATE TABLE `goods_attrs` (
                               `id` int(10) UNSIGNED NOT NULL,
                               `store_id` int(11) NOT NULL COMMENT '店铺ID',
                               `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '属性名称',
                               `sort` smallint(6) NOT NULL COMMENT '排序',
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL,
                               `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_attr_maps`
--

CREATE TABLE `goods_attr_maps` (
                                   `id` bigint(20) NOT NULL,
                                   `goods_id` int(11) NOT NULL COMMENT '产品ID',
                                   `attr_id` int(11) NOT NULL COMMENT '属性ID',
                                   `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '别名',
                                   `index` int(11) NOT NULL DEFAULT '0' COMMENT '排序 asc',
                                   `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_attr_values`
--

CREATE TABLE `goods_attr_values` (
                                     `id` int(10) UNSIGNED NOT NULL,
                                     `goods_attr_id` int(11) NOT NULL COMMENT '规格ID',
                                     `store_id` int(11) NOT NULL COMMENT '店铺ID',
                                     `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '属性名称',
                                     `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
                                     `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_attr_value_maps`
--

CREATE TABLE `goods_attr_value_maps` (
                                         `id` bigint(20) NOT NULL,
                                         `attr_map_id` int(11) NOT NULL COMMENT '产品属性关系ID',
                                         `goods_id` int(11) NOT NULL COMMENT '产品ID',
                                         `attr_id` int(11) NOT NULL COMMENT '属性ID',
                                         `attr_value_id` int(11) NOT NULL COMMENT '属性值ID',
                                         `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '别名',
                                         `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '图片',
                                         `index` int(11) NOT NULL DEFAULT '0' COMMENT '排序 asc',
                                         `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_classes`
--

CREATE TABLE `goods_classes` (
                                 `id` bigint(20) UNSIGNED NOT NULL,
                                 `parent_id` int(11) NOT NULL COMMENT '上级',
                                 `goods_class_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类标识',
                                 `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类名称',
                                 `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图标',
                                 `order` int(11) NOT NULL DEFAULT '1' COMMENT '排序',
                                 `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL,
                                 `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_contents`
--

CREATE TABLE `goods_contents` (
                                  `id` bigint(20) UNSIGNED NOT NULL,
                                  `goods_id` bigint(20) NOT NULL,
                                  `content` mediumtext COLLATE utf8mb4_unicode_ci,
                                  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_images`
--

CREATE TABLE `goods_images` (
                                `id` bigint(20) UNSIGNED NOT NULL,
                                `goods_id` bigint(20) NOT NULL,
                                `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                `order` int(11) DEFAULT '0',
                                `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_skus`
--

CREATE TABLE `goods_skus` (
                              `id` int(10) UNSIGNED NOT NULL COMMENT '自增 sku_id',
                              `goods_id` bigint(20) NOT NULL COMMENT '产品ID',
                              `goods_type` int(11) DEFAULT '1',
                              `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `attr_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '销售属性标识 - 链接，按小到大排序',
                              `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '图片',
                              `price` decimal(11,2) NOT NULL COMMENT '价格',
                              `cost_price` decimal(11,2) DEFAULT NULL COMMENT '成本价',
                              `line_price` decimal(11,2) DEFAULT '0.00' COMMENT '划线价',
                              `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '编码',
                              `sold_num` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
                              `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1:enable, 0:disable, -1:deleted',
                              `created_at` timestamp NULL DEFAULT NULL,
                              `updated_at` timestamp NULL DEFAULT NULL,
                              `deleted_at` timestamp NULL DEFAULT NULL,
                              `number_views` int(11) DEFAULT '0' COMMENT '浏览量',
                              `warrant_address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
                              `work_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
                              `chain_status` int(11) DEFAULT '0' COMMENT '上链状态',
                              `asset_id` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '资产id',
                              `img_url_360` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片360',
                              `img_url_850` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片850',
                              `img_url_origin` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片原图'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_sku_attr_value_maps`
--

CREATE TABLE `goods_sku_attr_value_maps` (
                                             `id` bigint(20) UNSIGNED NOT NULL,
                                             `goods_id` bigint(20) NOT NULL,
                                             `goods_sku_id` bigint(20) NOT NULL,
                                             `attr_id` int(11) NOT NULL DEFAULT '0',
                                             `attr_value_id` bigint(20) NOT NULL,
                                             `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `goods_sku_stocks`
--

CREATE TABLE `goods_sku_stocks` (
                                    `id` int(10) UNSIGNED NOT NULL COMMENT '自增ID',
                                    `sku_id` int(11) DEFAULT '0' COMMENT 'SKU ID',
                                    `goods_id` int(11) NOT NULL COMMENT '产品 ID',
                                    `quantity` int(11) NOT NULL COMMENT '库存',
                                    `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1:enable, 0:disable, -1:deleted',
                                    `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `searches`
--

CREATE TABLE `searches` (
                            `id` int(10) UNSIGNED NOT NULL,
                            `created_at` timestamp NULL DEFAULT NULL,
                            `updated_at` timestamp NULL DEFAULT NULL,
                            `deleted_at` timestamp NULL DEFAULT NULL,
                            `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
                            `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `shops`
--

CREATE TABLE `shops` (
                         `id` int(10) UNSIGNED NOT NULL,
                         `deleted_at` timestamp NULL DEFAULT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         `name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '店铺名称' COMMENT '店铺名称',
                         `image` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '店铺logo',
                         `user_id` int(11) DEFAULT '0' COMMENT '用户(商家)id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转储表的索引
--

--
-- 表的索引 `brands`
--
ALTER TABLE `brands`
    ADD PRIMARY KEY (`id`);

--
-- 表的索引 `goods`
--
ALTER TABLE `goods`
    ADD PRIMARY KEY (`id`);

--
-- 表的索引 `goods_attrs`
--
ALTER TABLE `goods_attrs`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `goods_attrs_store_id_name_unique` (`store_id`,`name`);

--
-- 表的索引 `goods_attr_maps`
--
ALTER TABLE `goods_attr_maps`
    ADD PRIMARY KEY (`id`),
    ADD KEY `goods_attr_map_goods_id_index` (`goods_id`);

--
-- 表的索引 `goods_attr_values`
--
ALTER TABLE `goods_attr_values`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `goods_attr_id` (`goods_attr_id`,`name`);

--
-- 表的索引 `goods_attr_value_maps`
--
ALTER TABLE `goods_attr_value_maps`
    ADD PRIMARY KEY (`id`),
    ADD KEY `goods_attr_value_map_attr_map_id_index` (`attr_map_id`),
    ADD KEY `goods_attr_value_map_goods_id_index` (`goods_id`);

--
-- 表的索引 `goods_classes`
--
ALTER TABLE `goods_classes`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `goods_classes_goods_class_key_unique` (`goods_class_key`);

--
-- 表的索引 `goods_contents`
--
ALTER TABLE `goods_contents`
    ADD PRIMARY KEY (`id`);

--
-- 表的索引 `goods_images`
--
ALTER TABLE `goods_images`
    ADD PRIMARY KEY (`id`);

--
-- 表的索引 `goods_skus`
--
ALTER TABLE `goods_skus`
    ADD PRIMARY KEY (`id`),
    ADD KEY `goods_skus_goods_id_attr_key_index` (`goods_id`,`attr_key`),
    ADD KEY `goods_skus_goods_id_index` (`goods_id`),
    ADD KEY `goods_skus_attr_key_index` (`attr_key`),
    ADD KEY `goods_id_name_index` (`goods_id`,`name`);

--
-- 表的索引 `goods_sku_attr_value_maps`
--
ALTER TABLE `goods_sku_attr_value_maps`
    ADD PRIMARY KEY (`id`);

--
-- 表的索引 `goods_sku_stocks`
--
ALTER TABLE `goods_sku_stocks`
    ADD PRIMARY KEY (`id`),
    ADD KEY `goods_id` (`goods_id`),
    ADD KEY `quantity` (`quantity`);

--
-- 表的索引 `searches`
--
ALTER TABLE `searches`
    ADD PRIMARY KEY (`id`);

--
-- 表的索引 `shops`
--
ALTER TABLE `shops`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `admin_shop_name_unique` (`name`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `brands`
--
ALTER TABLE `brands`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `goods`
--
ALTER TABLE `goods`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `goods_attrs`
--
ALTER TABLE `goods_attrs`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `goods_attr_maps`
--
ALTER TABLE `goods_attr_maps`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `goods_attr_values`
--
ALTER TABLE `goods_attr_values`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `goods_attr_value_maps`
--
ALTER TABLE `goods_attr_value_maps`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `goods_classes`
--
ALTER TABLE `goods_classes`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `goods_contents`
--
ALTER TABLE `goods_contents`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `goods_images`
--
ALTER TABLE `goods_images`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `goods_skus`
--
ALTER TABLE `goods_skus`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增 sku_id';

--
-- 使用表AUTO_INCREMENT `goods_sku_attr_value_maps`
--
ALTER TABLE `goods_sku_attr_value_maps`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `goods_sku_stocks`
--
ALTER TABLE `goods_sku_stocks`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID';

--
-- 使用表AUTO_INCREMENT `searches`
--
ALTER TABLE `searches`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `shops`
--
ALTER TABLE `shops`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
