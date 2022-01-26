<?php
class DbOperations
{
    private $conn;
    function __construct()
    {
        require_once dirname(__FILE__) . '/Constants.php';
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
    public function createWisdom($wisdom, $categories)
    {
        if (empty($wisdom)) {
            return false;
        } else {
            $stmt = $this->conn->prepare("INSERT INTO wisdom (wisdomText) VALUES (?)");
            $stmt->bind_param("s", $wisdom);
            if ($stmt->execute()) {
                $wisdomId = $this->conn->insert_id;
                $allCategories = $this->getAllCategories();
                if ($allCategories !== false) {
                    foreach ($categories as $cat) {
                        if (!in_array($cat, array_column($allCategories, 'name'))) {
                            $categoryId = $this->addCategory($cat);
                            if ($categoryId !== false) {
                                if ($this->addCategoryToWisdom($wisdomId, $categoryId) === false) {
                                    return false;
                                }
                            }
                        } else {
                            $categoryId = $this->retrieveCategoryIdFromItsName($cat);
                            if ($categoryId !== false) {
                                if ($this->addCategoryToWisdom($wisdomId, $categoryId) === false) {
                                    return false;
                                }
                            }
                        }
                    }
                    return $this->retrieveWisdomById($wisdomId);
                }
            } else {
                return false;
            }
        }
    }
    private function retrieveCategoryIdFromItsName($categoryName)
    {
        $stmt = $this->conn->prepare("SELECT id FROM categories WHERE categoryName = ?");
        $stmt->bind_param("s", $categoryName);
        if ($stmt->execute()) {
            $stmt->bind_result($id);
            $stmt->fetch();
            return $id;
        } else {
            return false;
        }
    }
    public function fetchTokenByUserName($username)
    {
        $stmt = $this->conn->prepare("SELECT token FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $stmt->bind_result($token);
            $stmt->fetch();
            return $token;
        } else {
            return false;
        }
    }
    public function getAllWisdoms()
    {
        $stmt = $this->conn->prepare("SELECT wisdom.id, wisdom.wisdomText, categories.id, categories.categoryName
                        FROM wisdom_category
                        INNER JOIN categories
                        ON wisdom_category.categoryId = categories.id  
                        INNER JOIN wisdom
                        ON wisdom_category.wisdomId = wisdom.id ORDER BY wisdom.id");
        if ($stmt->execute()) {
            $stmt->bind_result($wisdomId, $wisdomText, $categoryId, $categoryName);
            $wisdoms = array();

            $wisdom = array();
            $wisdom['id'] = null;
            $wisdom['text'] = "";
            $wisdom['categories'] = array();
            $category = array();
            while ($stmt->fetch()) {
                $category["id"] = $categoryId;
                $category["name"] = $categoryName;
                if ($wisdom['id'] === $wisdomId) {
                    array_push($wisdom['categories'], $category);
                } else {
                    if (!empty($wisdom['id'])) {
                        array_push($wisdoms, $wisdom);
                    }
                    $wisdom['id'] = $wisdomId;
                    $wisdom['text'] = $wisdomText;
                    $wisdom['categories'] = array();
                    array_push($wisdom['categories'], $category);
                }
            }
            array_push($wisdoms, $wisdom);
            shuffle($wisdoms);
            return $wisdoms;
        } else {
            return false;
        }
    }
    public function getRandomWisdom()
    {
        $stmt = $this->conn->prepare("SELECT wisdom.id, wisdom.wisdomText, categories.id, categories.categoryName
                        FROM wisdom_category
                        INNER JOIN categories
                        ON wisdom_category.categoryId = categories.id
                        INNER JOIN wisdom
                        ON wisdom_category.wisdomId = wisdom.id ORDER BY RAND()
LIMIT 1");
        if ($stmt->execute()) {
            $stmt->bind_result($wisdomId, $wisdomText, $categoryId, $categoryName);
            $wisdom = array();
            $wisdom['id'] = null;
            $wisdom['text'] = "";
            $wisdom['categories'] = array();
            $category = array();
            while ($stmt->fetch()) {
                $category["id"] = $categoryId;
                $category["name"] = $categoryName;
                if ($wisdom['id'] === $wisdomId) {
                    array_push($wisdom['categories'], $category);
                } else {
                    if (!empty($wisdom['id'])) {
                        array_push($wisdoms, $wisdom);
                    }
                    $wisdom['id'] = $wisdomId;
                    $wisdom['text'] = $wisdomText;
                    $wisdom['categories'] = array();
                    array_push($wisdom['categories'], $category);
                }
            }
            return $wisdom;
        } else {
            return false;
        }
    }
    public function editWisdom($wisdomId, $wisdomNewText, $wisdomCategories)
    {
        if ($this->editWisdomText($wisdomId, $wisdomNewText)) {
            $result = $this->getOldCategories($wisdomId);
            $allCategories = $this->getAllCategories();
            $oldCatNames = array();
            if ($result !== false) {
                foreach ($result as $cat) {
                    array_push($oldCatNames, $cat['name']);
                    if (!in_array($cat['name'], $wisdomCategories)) {
                        $this->removeCategoryForWisdom($wisdomId, $cat['id']);
                    }
                }
                foreach ($wisdomCategories as $newCat) {
                    $categoryId = $this->addCategory($newCat);
                    if ($categoryId !== false) {
                        if (!in_array($newCat, $allCategories)) {
                            if ($this->addCategoryToWisdom($wisdomId, $categoryId)) {
                                return OBJECT_CREATED;
                            }
                        } else {
                            if (!in_array($newCat, $oldCatNames)) {
                                if ($this->addCategoryToWisdom($wisdomId, $categoryId)) {
                                    return OBJECT_CREATED;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function getAllCategories()
    {
        $stmt = $this->conn->prepare("SELECT id, categoryName FROM categories");
        if ($stmt->execute()) {
            $stmt->bind_result($idsColumn, $namesColumn);
            $allCats = array();
            while ($stmt->fetch()) {
                $cat = array();
                $cat['id'] = $idsColumn;
                $cat['name'] = $namesColumn;
                array_push($allCats, $cat);
            }
            return $allCats;
        } else {
            return false;
        }
    }

    private function addCategory($categoryName)
    {
        $stmt = $this->conn->prepare("INSERT INTO categories (categoryName) VALUES (?)");
        $stmt->bind_param("s", $categoryName);
        if ($stmt->execute()) {
            return $wisdomId = $this->conn->insert_id;
        } else {
            return false;
        }
    }

    private function addCategoryToWisdom($wisdomId, $categoryId)
    {
        $stmt = $this->conn->prepare("INSERT INTO wisdom_category (wisdomId, categoryId) VALUES (?, ?)");
        $stmt->bind_param("ii", $wisdomId, $categoryId);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function editWisdomText($wisdomId, $wisdomNewText)
    {
        $stmt = $this->conn->prepare("UPDATE wisdom SET wisdomText = ? WHERE id = ?");
        $stmt->bind_param("si", $wisdomNewText, $wisdomId);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    private function getOldCategories($wisdomId)
    {
        $stmt = $this->conn->prepare("SELECT categoryName, categories.id FROM wisdom_category INNER JOIN categories ON categories.id = wisdom_category.categoryId WHERE wisdom_category.wisdomId = ?");
        $stmt->bind_param("i", $wisdomId);
        if ($stmt->execute()) {
            $stmt->bind_result($oldName, $id);
            $oldCats = array();
            while ($stmt->fetch()) {
                $cat = array();
                $cat['id'] = $id;
                $cat['name'] = $oldName;
                array_push($oldCats, $cat);
            }
            return $oldCats;
        } else {
            return OBJECT_NOT_CREATED;
        }
    }

    private function removeCategoryForWisdom($wisdomId, $categoryIdToBeRemoved)
    {
        $stmt = $this->conn->prepare("DELETE FROM wisdom_category WHERE categoryId = ? AND wisdomId = ?");
        $stmt->bind_param("ii", $categoryIdToBeRemoved, $wisdomId);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function searchWisdom($searchText)
    {
        $newSearchText = $searchText;
        $newSearchText = str_replace('ة', '(ة|ه)', $newSearchText);
        $newSearchText = str_replace('ه', '(ة|ه)', $newSearchText);
        $newSearchText = str_replace('ا', '(إ|أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
        $newSearchText = str_replace('أ', '(إ|أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
        $newSearchText = str_replace('إ', '(إ|أ|ا|آ|ء|ئ)', $newSearchText);
        $newSearchText = str_replace('آ', '(إ|أ|ا|آ|ء)', $newSearchText);
        $newSearchText = str_replace('ء', '(أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
        $newSearchText = str_replace('و', '(و|ؤ)', $newSearchText);
        $newSearchText = str_replace('ي', '(ئ|ي|ى)', $newSearchText);
        $newSearchText = str_replace('ى', '(ئ|ي|ى)', $newSearchText);

        $regular_spaces = str_replace(' ', "\xc2\xa0", $searchText);
        $regular_spaces = str_replace('ة', '(ة|ه)', $regular_spaces);
        $regular_spaces = str_replace('ه', '(ة|ه)', $regular_spaces);
        $regular_spaces = str_replace('ا', '(إ|أ|ا|آ|ء|ئ|ؤ)', $regular_spaces);
        $regular_spaces = str_replace('أ', '(إ|أ|ا|آ|ء|ئ|ؤ)', $regular_spaces);
        $regular_spaces = str_replace('إ', '(إ|أ|ا|آ|ء|ئ)', $regular_spaces);
        $regular_spaces = str_replace('آ', '(إ|أ|ا|آ|ء)', $regular_spaces);
        $regular_spaces = str_replace('ء', '(أ|ا|آ|ء|ئ|ؤ)', $regular_spaces);
        $regular_spaces = str_replace('و', '(و|ؤ)', $regular_spaces);
        $regular_spaces = str_replace('ي', '(ئ|ي|ى)', $regular_spaces);
        $regular_spaces = str_replace('ى', '(ئ|ي|ى)', $regular_spaces);
        $newSearchText2 = $regular_spaces;
        $stmt = $this->conn->prepare('SELECT id, wisdomText FROM wisdom WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(wisdomText, "-", ""), "َ", ""), "ّ", ""), "ً", ""), "ِ", ""), "ٍ", ""),"ُ", ""),"ٌ", ""),"ْ", ""), "ْ", "") REGEXP ? OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(wisdomText, "-", ""), "َ", ""), "ً", ""), "ِ", ""), "ٍ", ""),"ُ", ""),"ٌ", ""),"ْ", ""), "ْ", "") REGEXP ? ORDER BY id LIMIT 1000');
        $stmt->bind_param("ss", $newSearchText, $newSearchText2);
        $wisdoms = array();
        $wisdom = array();
        $wisdom["id"] = null;
        $wisdom["text"] = "";
        $wisdom["categories"] = array();
        $finalWisdoms = array();
        if ($stmt->execute()) {
            $stmt->bind_result($wisdomIdColumn, $wisdomTextColumn);
            while ($stmt->fetch()) {
                $wisdom['id'] = $wisdomIdColumn;
                $wisdom['text'] = $wisdomTextColumn;
                array_push($wisdoms, $wisdom);
            }
            foreach ($wisdoms as $wisd) {
                $res = $this->getOldCategories($wisd['id']);
                if ($res !== false) {
                    $wisd["categories"] = $res;
                    array_push($finalWisdoms, $wisd);
                }
            }
            return $finalWisdoms;
        } else {
            return false;
        }
    }

    public function exploreCategory($categoryId)
    {
        $stmt = $this->conn->prepare("SELECT wisdom.id, wisdom.wisdomText
                        FROM wisdom_category
                        INNER JOIN categories
                        ON wisdom_category.categoryId = categories.id  
                        INNER JOIN wisdom
                        ON wisdom_category.wisdomId = wisdom.id WHERE wisdom_category.categoryId = ? ORDER BY wisdom.id LIMIT 1000");
        $stmt->bind_param("i", $categoryId);
        $wisdoms = array();
        $wisdom = array();
        $wisdom["id"] = null;
        $wisdom["text"] = "";
        $wisdom["categories"] = array();
        $finalWisdoms = array();

        if ($stmt->execute()) {
            $stmt->bind_result($wisdomIdColumn, $wisdomTextColumn);
            while ($stmt->fetch()) {
                $wisdom['id'] = $wisdomIdColumn;
                $wisdom['text'] = $wisdomTextColumn;
                array_push($wisdoms, $wisdom);
            }
            foreach ($wisdoms as $wisd) {
                $res = $this->getOldCategories($wisd['id']);
                if ($res !== false) {
                    $wisd["categories"] = $res;
                    array_push($finalWisdoms, $wisd);
                }
            }
            shuffle($finalWisdoms);
            return $finalWisdoms;
        } else {
            return false;
        }
    }
    public function addWisdomDeleted($wisdomId)
    {
        $stmt = $this->conn->prepare("INSERT INTO wisdom_deletes (id, oldWisdomText) VALUES (?, ?)");
        $stmt->bind_param("is", $wisdomId, $this->retrieveWisdomById($wisdomId)['text']);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function removeWisdom($wisdomId)
    {
        $stmt = $this->conn->prepare("DELETE FROM wisdom WHERE id = ?");
        $stmt->bind_param("i", $wisdomId);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function loadBoOmarDb()
    {
        $wisdoms = array();
        $stmt = $this->conn->prepare("SELECT wisdom, cat1, cat2 FROM boOmarDb");
        if ($stmt->execute()) {
            $stmt->bind_result($wisdomColumn, $cat1Column, $cat2Column);
            while ($stmt->fetch()) {
                $wisdom = array();
                $wisdom['categories'] = array();
                array_push($wisdom['categories'], $cat1Column);
                // array_push($wisdom['categories'], $cat2Column);
                $wisdom['wisdomText'] = $wisdomColumn;
                array_push($wisdoms, $wisdom);
            }
            return $wisdoms;
        } else {
            return false;
        }
    }
    public function loadBoOmarDb2()
    {
        $wisdoms = array();
        $stmt = $this->conn->prepare("SELECT wisdom FROM boOmarDb2");
        if ($stmt->execute()) {
            $stmt->bind_result($wisdomColumn);
            while ($stmt->fetch()) {
                if (!empty($wisdomColumn)) {
                    $wisdom = array();
                    $wisdom['categories'] = array();
                    array_push($wisdom['categories'], "جديدة ٢");
                    $wisdom['wisdomText'] = $wisdomColumn;
                    array_push($wisdoms, $wisdom);
                }
            }
            return $wisdoms;
        } else {
            return false;
        }
    }
    public function loadBoOmarDb3()
    {
        $wisdoms = array();
        $stmt = $this->conn->prepare("SELECT wisdom FROM boOmarDb3");
        if ($stmt->execute()) {
            $stmt->bind_result($wisdomColumn);
            while ($stmt->fetch()) {
                if (!empty($wisdomColumn)) {
                    $wisdom = array();
                    $wisdom['categories'] = array();
                    array_push($wisdom['categories'], "جديدة ٣");
                    $wisdom['wisdomText'] = $wisdomColumn;
                    array_push($wisdoms, $wisdom);
                }
            }
            return $wisdoms;
        } else {
            return false;
        }
    }
    public function retrieveWisdomById($id)
    {
        $stmt = $this->conn->prepare("SELECT id, wisdomText FROM wisdom WHERE id = ?");
        $stmt->bind_param("i", $id);
        $wisdom = array();
        $wisdom["id"] = null;
        $wisdom["text"] = "";
        $wisdom["categories"] = array();
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($wisdomIdColumn, $wisdomTextColumn);
                while ($stmt->fetch()) {
                    $wisdom['id'] = $wisdomIdColumn;
                    $wisdom['text'] = $wisdomTextColumn;
                }
                $res = $this->getOldCategories($wisdom['id']);
                if ($res !== false) {
                    $wisdom["categories"] = $res;
                }
                return $wisdom;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function createWisdoms($wisdoms)
    {
        if (empty($wisdoms)) {
            return false;
        } else {
            foreach ($wisdoms as $wisdom) {
                $stmt = $this->conn->prepare("INSERT INTO wisdom (wisdomText) VALUES (?)");
                $stmt->bind_param("s", $wisdom['wisdomText']);
                if ($stmt->execute()) {
                    $wisdomId = $this->conn->insert_id;
                    $allCategories = $this->getAllCategories();
                    if ($allCategories !== false) {
                        foreach ($wisdom['categories'] as $cat) {
                            if (!in_array($cat, array_column($allCategories, 'name'))) {
                                $categoryId = $this->addCategory($cat);
                                if ($categoryId !== false) {
                                    if ($this->addCategoryToWisdom($wisdomId, $categoryId) === false) {
                                        echo ($wisdomId);
                                        return true;
                                    }
                                }
                            } else {
                                $categoryId = $this->retrieveCategoryIdFromItsName($cat);
                                if ($categoryId !== false) {
                                    if ($this->addCategoryToWisdom($wisdomId, $categoryId) === false) {
                                        echo ($wisdomId);
                                        return true;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    return false;
                }
            }
        }
    }
    public function addWisdomChange($wisdomId, $oldWisdom)
    {
        $stmt = $this->conn->prepare("INSERT INTO wisdom_changes (wisdomId, wisdomText) VALUES (?, ?)");
        $stmt->bind_param("is", $wisdomId, $oldWisdom);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
