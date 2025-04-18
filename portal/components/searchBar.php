<!-- $search_message is a variable thus need to be passed into the component -->
<?php 
    if(!isset($search_message))
        $search_message = "Search Product";
    if(!isset($searchOn))
        $searchOn = "";
?>
<section class="section-search">
<div class="searchForm">
    <div class="searchBar".<?php echo $searchOn?>>
        <input type="text" name="search" placeholder="<?php echo $search_message;?>" class="search-input" id="search_input">
        <button class="searchButton">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                <path d="M21 21l-6 -6"></path>
            </svg>
        </button>
    </div>
</div>
<div class="searchList">
    <!-- <a href="#" class="searchList__item">
        <div class="item_searched">
            <img src="./images/pexels-alex-andrews-821651.jpg" alt="product_image" class="item_searched__image">
            <div class="item_searched__details">
                <div class="item_searched__details__product_name">ITR Filing : 101</div>
                <div class="item_searched__details__company_name">Tax Sarathi</div>
                <div class="item_searched__details__description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis esse nisi natus obcaecati libero ...</div>
            </div>
        </div>
    </a>
    <a href="#" class="searchList__item">Product 2</a>
    <a href="#" class="searchList__item">Product 3</a> -->
</div>
</section>