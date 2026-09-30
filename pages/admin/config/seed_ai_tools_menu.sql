-- ============================================================
-- Seed: "AI Tools" dropdown di Main Navigation
-- Jalankan di phpMyAdmin / mysql client
-- Aman dijalankan berulang (idempotent, cek dulu sebelum insert)
-- ============================================================

SET @menu_id = (SELECT id FROM nav_menus WHERE slug = 'main-nav' LIMIT 1);

-- 1) Parent: AI Tools
INSERT INTO `nav_menu_items` (`menu_id`, `parent_id`, `title`, `url`, `target`, `sort_order`)
SELECT @menu_id, NULL, 'AI Tools', '/feature/ai-for-cosmetic-industry', '_self', 2
WHERE @menu_id IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM `nav_menu_items`
    WHERE `menu_id` = @menu_id AND `title` = 'AI Tools' AND `parent_id` IS NULL
  );

SET @parent_id = (
  SELECT id FROM `nav_menu_items`
  WHERE `menu_id` = @menu_id AND `title` = 'AI Tools' AND `parent_id` IS NULL
  LIMIT 1
);

-- 2) Child: AI for Cosmetic Industry
--    Tambah child lain (sort_order 2, 3, 4, ...) cukup insert di blok yang sama,
--    dropdown di header otomatis jadi 2 kolom saat > 4 item.
INSERT INTO `nav_menu_items` (`menu_id`, `parent_id`, `title`, `url`, `target`, `sort_order`)
SELECT @menu_id, @parent_id, 'AI for Cosmetic Industry', '/feature/ai-for-cosmetic-industry', '_self', 1
WHERE @parent_id IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM `nav_menu_items`
    WHERE `parent_id` = @parent_id AND `title` = 'AI for Cosmetic Industry'
  );

-- Verifikasi
SELECT id, parent_id, title, url, sort_order, is_active
FROM `nav_menu_items`
WHERE `menu_id` = @menu_id
ORDER BY parent_id IS NOT NULL, parent_id, sort_order, id;
