<?php
/*
Plugin Name: Quotes Admin Display
Description: Displays submitted quotes in WordPress admin with pagination, filters, and export.
Version: 1.1
*/

// ===== Add Menu =====
add_action('admin_menu', function() {
    add_menu_page(
        'Quotes', 
        'Quotes', 
        'manage_options', 
        'quotes-admin', 
        'quotes_admin_page', 
        'dashicons-media-text', 
        26
    );
});

// ===== WP_List_Table =====
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Quotes_List_Table extends WP_List_Table {

    function get_columns() {
        return [
            'cb'        => '<input type="checkbox" />',
            'name'      => 'Name',
            'email'     => 'Email',
            'number'    => 'Number',
            'property'  => 'Property of Interest',
            'country'   => 'Country',
            'quote_form_title' => 'Quote Form Title',
            'created_at'=> 'Created At',
        ];
    }

    function get_hidden_columns() {
        return [];
    }

    function get_sortable_columns() {
        return [
            'name'       => ['name', false],
            'created_at' => ['created_at', true], // ✅ default sort by created_at
        ];
    }

    function column_cb($item) {
        return sprintf('<input type="checkbox" name="quote[]" value="%s" />', $item->id);
    }

    function column_name($item) {
        return esc_html($item->first_name . ' ' . $item->last_name);
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'email':
                return esc_html($item->email);
            case 'number':
                return esc_html($item->number);
            case 'property':
                return esc_html($item->property_of_interest);
            case 'country':
                return esc_html($item->country_of_residence);
            case 'quote_form_title':
                return esc_html($item->quote_form_title);
            case 'created_at':
                return esc_html($item->created_at);
            default:
                return '';
        }
    }

    function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'quotes';

        $per_page = 10;
        $current_page = $this->get_pagenum();

        // Filtering
        $where = "WHERE 1=1";
        if (!empty($_REQUEST['s'])) {
            $search = esc_sql($_REQUEST['s']);
            $where .= " AND (first_name LIKE '%$search%' 
                        OR last_name LIKE '%$search%' 
                        OR email LIKE '%$search%' 
                        OR property_of_interest LIKE '%$search%')";
        }
        if (!empty($_REQUEST['date_from']) && !empty($_REQUEST['date_to'])) {
            $date_from = esc_sql($_REQUEST['date_from']);
            $date_to = esc_sql($_REQUEST['date_to']);
            $where .= " AND DATE(created_at) BETWEEN '$date_from' AND '$date_to'";
        }

        // ✅ Sorting
        $orderby = (!empty($_REQUEST['orderby'])) ? esc_sql($_REQUEST['orderby']) : 'created_at';
        $order   = (!empty($_REQUEST['order'])) ? esc_sql($_REQUEST['order']) : 'DESC';

        // Count total
        $total_items = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name $where");

        // Query with limit + sort
        $offset = ($current_page - 1) * $per_page;
        $items = $wpdb->get_results("SELECT * FROM $table_name $where ORDER BY $orderby $order LIMIT $per_page OFFSET $offset");

        $this->items = $items;

        // ✅ Important: set headers
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [$columns, $hidden, $sortable];

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);
    }
}



// ===== Admin Page =====
function quotes_admin_page() {
    $quotesTable = new Quotes_List_Table();
    $quotesTable->prepare_items();
    ?>
    <div class="wrap">
        <h1>Quotes</h1>

        <!-- Filter Form -->
        <form method="get">
            <input type="hidden" name="page" value="quotes-admin" />
            <input type="search" name="s" value="<?php echo esc_attr($_REQUEST['s'] ?? ''); ?>" placeholder="Search..." />
            <input type="date" name="date_from" value="<?php echo esc_attr($_REQUEST['date_from'] ?? ''); ?>" />
            <input type="date" name="date_to" value="<?php echo esc_attr($_REQUEST['date_to'] ?? ''); ?>" />
            <button class="button">Filter</button>
            <a href="<?php echo esc_url(add_query_arg(['export' => 'csv'])); ?>" class="button button-primary">Export CSV</a>
        </form>

        <!-- Table -->
        <form method="post">
            <?php $quotesTable->display(); ?>
        </form>
    </div>
    <?php
}

// ===== CSV Export =====
add_action('admin_init', function() {
    if (isset($_GET['page']) && $_GET['page'] === 'quotes-admin' && isset($_GET['export']) && $_GET['export'] === 'csv') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'quotes';

        $where = "WHERE 1=1";
        $date_from = $_REQUEST['date_from'] ?? '';
        $date_to   = $_REQUEST['date_to'] ?? '';
        $search    = $_REQUEST['s'] ?? '';

        if (!empty($search)) {
            $search = esc_sql($search);
            $where .= " AND (first_name LIKE '%$search%' 
                        OR last_name LIKE '%$search%' 
                        OR email LIKE '%$search%' 
                        OR property_of_interest LIKE '%$search%')";
        }

        if (!empty($date_from) && !empty($date_to)) {
            $date_from = esc_sql($date_from);
            $date_to   = esc_sql($date_to);
            $where .= " AND DATE(created_at) BETWEEN '$date_from' AND '$date_to'";
        }

        $rows = $wpdb->get_results("SELECT * FROM $table_name $where ORDER BY created_at DESC", ARRAY_A);

        // ✅ Build filename dynamically
        $filename = "quotes";
        if (!empty($date_from) && !empty($date_to)) {
            $filename .= "-{$date_from}-to-{$date_to}";
        }
        if (!empty($search)) {
            $filename .= "-search-" . sanitize_title($search);
        }
        if ($filename === "quotes") {
            $filename .= "-all";
        }
        $filename .= ".csv";

        header('Content-Type: text/csv');
        header("Content-Disposition: attachment;filename={$filename}");

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Name', 'Email', 'Number', 'Property of Interest', 'Country', 'Quote Form Title', 'Created At']);
        foreach ($rows as $row) {
            fputcsv($output, [
                $row['first_name'] . ' ' . $row['last_name'],
                $row['email'],
                $row['number'],
                $row['property_of_interest'],
                $row['country_of_residence'],
                $row['quote_form_title'],
                $row['created_at']
            ]);
        }
        fclose($output);
        exit;
    }
});
