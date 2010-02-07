<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Digg pagination style
 * 
 * @preview  « Previous  1 2 … 5 6 7 8 9 10 11 12 13 14 … 25 26  Next »
 */
?>
<ul class="tab-pagination">
  <?php if ($total_pages < 13): /* « Previous  1 2 3 4 5 6 7 8 9 10 11 12  Next » */ ?>

    <?php for ($i = 1; $i <= $total_pages; $i++)
    {
      $start = $items_per_page*($i-1)+1;
      $end = ($start <= $total_items AND $total_items <= ($start+$items_per_page))
        ? $total_items
        : $items_per_page*($i-1) + $items_per_page;
      echo '<li>';
      if ($i == $current_page): ?>
        <a href="#" class="active"><?php echo "$start - $current_last_item"?></a>
      <?php else: ?>
        <a href="<?php echo str_replace('{page}', $i, $url) ?>"><?php echo "$start - $end"?></a>
      <?php endif;
      echo '</li>';
    }
      endif;?>
</ul>