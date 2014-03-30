jQuery(function($) {
   $.ajaxSetup({
       cache: false
   });

   $('a#reload-posts').click(function(e) {
      e.preventDefault();

      $blogs = $('#blogs');
      $throbber = $blogs.find('img.throbber');
      $throbber.show();
      $blogs.find('div').remove();

      $.get( "/ajax/posts", function( data ) {
         for (i=0; i<data.length; i++)
         {
            var post = data[i];
  
            $blogpost = $('<div style="margin-bottom: 25px; border: solid thin black;">');
	    $titleanchor = $('<a>')
	                   .attr('href', '/ajax/content/' + post.id)
	                   .addClass('post-content')
			   .html(post.title);
	    $blogpost.append($titleanchor);
  
	    $metadiv = $('<div style="margin-top: 25px;">');
	    var postdate = new Date(Date.parse(post.date));
	    $metadiv.html('Posted by: ' + post.author.name + ' at ' + postdate.toLocaleString());
	    $blogpost.append($metadiv);
  
            var tags_str = '';
	    for (t=0; t<post.tags.length; t++)
	    {
	       if (t==0) tags_str += post.tags[t].tag;
	       else tags_str += ', ' + post.tags[t].tag;
	    }
	    $tagsdiv = $('<div style="margin-top: 25px;">');
	    $tagsdiv.html('Tags: ' + tags_str); 
	    $blogpost.append($tagsdiv);
  
            $throbber.hide();

	    $blogs.append($blogpost);
         }
      }, 'json');
   });

   $('a.post-content').click(function(e) {
      e.preventDefault();

      $container = $(this).parent('div');
      $container.find('blockquote').remove();
      console.log($container);
      $container.append($('<div class="throbber" style="margin-top: 25px;"><img src="/images/ajax-loader.gif" /></div>'));

      var target = $(this).attr('href');
      $.get( target, function( data ) {
         console.log(data.content);
	 $container.append($('<blockquote>' + data.content + '</blockquote>'));
	 $container.find('.throbber').remove();
      }, 'json');
   });
});
