<?php

$util = new TaxonomyUtil();
$hashed = '';
$taxQuery = null;
$terms = array();

$age = null;
if ( get_field('lsb_frontpage_filter_age') ) {
  $age = get_field('lsb_frontpage_filter_age');
} else {
  $age = get_sub_field('section_tax_age');
}
if ( is_array($age) ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_age',
    'field' => 'id',
    'terms' => array_map(array($util, 'get_id'), $age),
  );
  $terms = array_merge($terms, array_map(array($util, 'get_name'), $age));
}

$lsb_cat = null;
if ( get_field('lsb_frontpage_filter_lsb_cat') ) {
  $lsb_cat = get_field('lsb_frontpage_filter_lsb_cat');
} else {
  $lsb_cat = get_sub_field('section_tax_lsb_cat');
}
if ( is_array($lsb_cat) ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_lsb_cat',
    'field' => 'id',
    'terms' => array_map(array($util, 'get_id'), $lsb_cat),
  );
  $terms = array_merge($terms, array_map(array($util, 'get_name'), $lsb_cat));
} else if ( $lsb_cat ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_lsb_cat',
    'field' => 'id',
    'terms' => array( $lsb_cat->term_id )
  );
  $terms[] = $lsb_cat->name;
}

$audience = null;
if ( get_field('lsb_frontpage_filter_audience') ) {
  $audience = get_field('lsb_frontpage_filter_audience');
} else {
  $audience = get_sub_field('section_tax_audience');
}
if ( is_array($audience) ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_audience',
    'field' => 'id',
    'terms' => array_map(array($util, 'get_id'), $audience),
  );
  $terms = array_merge($terms, array_map(array($util, 'get_name'), $audience));
}

$customization = null;
$customization = get_sub_field('section_customization');
if ( is_array($customization) ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_customization',
    'field' => 'id',
    'terms' => array_map(array($util, 'get_id'), $customization),
  );
  $terms = array_merge($terms, array_map(array($util, 'get_name'), $customization));
}

$author = null;
$author = get_sub_field('section_author');
if ( is_array($author) ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_author',
    'field' => 'id',
    'terms' => array_map(array($util, 'get_id'), $author),
  );
  $terms = array_merge( $terms, array_map(array($util, 'get_name'), $author) );
}

$genre = null;
$genre = get_sub_field('section_genre');
if ( is_array($genre) ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_genre',
    'field' => 'id',
    'terms' => array_map(array($util, 'get_id'), $genre),
  );
  $terms = array_merge( $terms, array_map(array($util, 'get_name'), $genre) );
}

$topic = null;
$topic = get_sub_field('section_topic');
if ( is_array($topic) ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_topic',
    'field' => 'id',
    'terms' => array_map(array($util, 'get_id'), $topic),
  );
  $terms = array_merge( $terms, array_map(array($util, 'get_name'), $topic) );
}

$language = null;
$language = get_sub_field('section_language');
if ( is_array($language) ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_language',
    'field' => 'id',
    'terms' => array_map(array($util, 'get_id'), $language),
  );
  $terms = array_merge( $terms, array_map(array($util, 'get_name'), $language) );
}

$publisher = null;
$publisher = get_sub_field('section_publisher');
if ( is_array($publisher) ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_publisher',
    'field' => 'id',
    'terms' => array_map(array($util, 'get_id'), $publisher),
  );
  $terms = array_merge( $terms, array_map(array($util, 'get_name'), $publisher) );
}

$series = null;
$series = get_sub_field('section_series');
if ( is_array($series) ) {
  $taxQuery[] = array(
    'taxonomy' => 'lsb_tax_series',
    'field' => 'id',
    'terms' => array_map(array($util, 'get_id'), $series),
  );
  $terms = array_merge( $terms, array_map(array($util, 'get_name'), $series) );
}

var_dump($taxQuery);

$args = array(
    'post_type' => 'lsb_book',
    'update_post_term_cache' => false,
    'update_post_meta_cache' => false,
    'no_found_rows' => true,
    'post_status'=>'publish',
    'tax_query' => $taxQuery
);

$orderby = null;
$orderby = get_sub_field('section_orderby');
$order = get_sub_field('section_order');

if ($orderby) {
    switch($orderby) {
      case 'random':
        $args['orderby'] = 'rand';
        break;
      case 'added':
        $args['orderby'] = 'date';
        $args['order'] = $order;
        break;
      case 'published':
        $args['meta_key'] = 'lsb_published_year';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = $order;
        $args['meta_query'] = array(
          array(
            'key' => 'lsb_published_year'
          )
        );
        break;
      default:
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        break;
    }
}

$hashed = hash('md5', implode( $terms ) . ' ' . $orderby . ' ' . $order);
if ( false == ( $books = get_transient( $hashed ) ) ) {
  $books = new WP_Query ($args);
  set_transient( $hashed, $books, 3600 );
}

?>

<?php if ( $books->have_posts() ) : ?>

  <div class="book-section">
    <div class="book-section-header page-header">

      <h1>
        <?php the_sub_field('section_header'); ?>

        <?php if ( get_sub_field('section_sub_header') ) : ?>
          <small>| <?php the_sub_field('section_sub_header'); ?></small>
        <?php endif; ?>

        <?php if ( get_sub_field('section_description') ) : ?>
          <button type="button" class="btn-link" aria-hidden="true">
            <span class="glyphicon glyphicon-info-sign"></span>
          </button>
        <?php endif; ?>

      </h1>

      <?php if ( get_sub_field('section_description') ) : ?>
        <div class="alert alert-info description sr-only">
          <button type="button" class="close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only"><?php echo __('Lukk', 'lsb_boksok'); ?></span>
          </button>
          <?php the_sub_field('section_description'); ?>
          <p>
            <a href="<?php echo get_search_link( implode( ' ', $terms ) ); ?> ">
              <?php echo __('Søk etter bøker i seksjonen', 'lsb_boksok'); ?>
              <?php the_sub_field('section_header') ?>.
            </a>
          </p>
        </div>
      <?php endif; ?>

    </div>

    <div class="book-section-body">

      <span aria-hidden="true" class="book-section-left-scroll hidden-xs glyphicon glyphicon-chevron-left"></span>
      <span aria-hidden="true" class="book-section-right-scroll hidden-xs glyphicon glyphicon-chevron-right"></span>

      <div class="book-section-scroll">
        <?php while ( $books->have_posts() ) : $books->the_post(); ?>
          <?php get_template_part('templates/content-summary', 'lsb_book'); ?>
        <?php endwhile; ?>
      </div>

    </div>

  </div>
<?php endif; ?>

<?php wp_reset_query(); ?>
