-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-02-22 21:26:46
-- 服务器版本： 5.7.30-log
-- PHP 版本： 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- 表的结构 `goods`
--

CREATE TABLE `goods` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `user_id` int(11) NOT NULL COMMENT '用户ID',
                         `shop_id` int(11) NOT NULL COMMENT '商家ID',
                         `goods_class_path` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `goods_class_id` int(11) NOT NULL COMMENT '分类ID',
                         `brand_id` int(11) NOT NULL DEFAULT '0',
                         `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品名称',
                         `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述',
                         `on_shelf` tinyint(1) NOT NULL DEFAULT '0' COMMENT '上架',
                         `one_attr` tinyint(1) NOT NULL,
                         `price` decimal(8,2) DEFAULT '0.00',
                         `cost_price` decimal(8,2) DEFAULT '0.00',
                         `line_price` decimal(8,2) DEFAULT '0.00',
                         `stock_num` int(11) DEFAULT '0',
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         `deleted_at` timestamp NULL DEFAULT NULL
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
                              `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `attr_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '销售属性标识 - 链接，按小到大排序',
                              `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '图片',
                              `price` decimal(8,2) NOT NULL COMMENT '价格',
                              `cost_price` decimal(8,2) DEFAULT NULL COMMENT '成本价',
                              `line_price` decimal(8,2) DEFAULT '0.00' COMMENT '划线价',
                              `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '编码',
                              `sold_num` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
                              `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1:enable, 0:disable, -1:deleted',
                              `created_at` timestamp NULL DEFAULT NULL,
                              `updated_at` timestamp NULL DEFAULT NULL,
                              `deleted_at` timestamp NULL DEFAULT NULL
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
                                    `sku_id` int(11) NOT NULL COMMENT 'SKU ID',
                                    `goods_id` int(11) NOT NULL COMMENT '产品 ID',
                                    `quantity` int(11) NOT NULL COMMENT '库存',
                                    `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1:enable, 0:disable, -1:deleted',
                                    `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转储表的索引
--

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
    ADD KEY `goods_skus_attr_key_index` (`attr_key`);

--
-- 表的索引 `goods_sku_attr_value_maps`
--
ALTER TABLE `goods_sku_attr_value_maps`
    ADD PRIMARY KEY (`id`);

--
-- 表的索引 `goods_sku_stocks`
--
ALTER TABLE `goods_sku_stocks`
    ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
