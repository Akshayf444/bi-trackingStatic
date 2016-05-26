<?php

class Autopaginate {

    protected $table_name = '';
    public $current_page;
    public $per_page;
    public $total_count;
    public $where = array();

    public function __construct($page, $per_page = 1, $total_count) {
        $this->current_page = (int) $page;
        $this->per_page = (int) $per_page;
        $this->total_count = (int) $total_count;

    }

    public function offset() {
        return ($this->current_page - 1) * $this->per_page;
    }

    public function Paginate($per_page, $page) {
        $this->per_page = (int) $per_page;
        $this->current_page = (int) $page;
        $this->total_count = (int) $this->totalCount();
        $sql = 'SELECT * FROM ' . $this->table_name;
        if (isset($where) && !empty($where)) {
            $sql .=join(" ", $where);
        }
        $sql .=' LIMIT ' . $this->per_page;
        $sql .=' OFFSET ' . $this->offset();
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function totalCount() {
        $sql = 'SELECT COUNT(*) AS count FROM ' . $this->table_name;
        if (isset($where) && !empty($where)) {
            $sql .=join(" ", $where);
        }
        $query = $this->db->query($sql);
        $result_array = $query->row_array();
        ;
        if (!empty($result_array)) {
            $result_array = array_shift($result_array);
            return $result_array->count;
        }
    }

    public function total_pages() {
        return ceil($this->total_count / $this->per_page);
    }

    public function previous_page() {
        return $this->current_page - 1;
    }

    public function next_page() {
        return $this->current_page + 1;
    }

    public function has_previous_page() {
        return $this->previous_page() >= 1 ? true : false;
    }

    public function has_next_page() {
        return $this->next_page() <= $this->total_pages() ? true : false;
    }

    public function renderPaging($pagename = '', $page = 1) {
        $output = '';
        $nextFivePages = array($page + 1, $page + 2, $page + 3, $page + 4, $page + 5);
        $previousPages = array($page - 5, $page - 4, $page - 3, $page - 2, $page - 1);
        if ($this->total_pages() > 1) {
            $output.= '<ul class="pagination pagination-sm">';
            if ($this->has_previous_page()) {
                $output.=$this->getLink($pagename, $this->previous_page(), 'Previous');
            }
            if ($previousPages[0] > 1) {
                $output.=$this->getLink($pagename, 1, 'First');
            }

            if (!empty($previousPages)) {
                foreach ($previousPages as $pages) {
                    if ($pages > 0) {
                        $output.=$this->getLink($pagename, $pages, $pages);
                    }
                }
            }
            $output.=$this->getLink($pagename, $page, $page, 'active');

            if (!empty($nextFivePages)) {
                foreach ($nextFivePages as $pages) {
                    if ($pages <= $this->total_pages()) {
                        $output.=$this->getLink($pagename, $pages, $pages);
                    }
                }
            }

            if (end($nextFivePages) < $this->total_pages()) {
                $output.=$this->getLink($pagename, $this->total_pages(), 'Last');
            }
            if ($this->has_next_page()) {
                $output.=$this->getLink($pagename, $this->next_page(), 'Next');
            }
            $output.= '</ul>';
        }

        return $output;
    }

    private function getLink($pagename, $linkno, $linkText, $active = '') {
        if ($active != '') {
            $link = '<li class="active"><a href="' . $pagename . '?page=' . $linkno . '">' . $linkText . '</a></li> ';
        } else {
            $link = '<li><a href="' . $pagename . '?page=' . $linkno . '">' . $linkText . '</a></li> ';
        }
        return $link;
    }

}
