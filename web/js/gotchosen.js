jQuery(function($) {
   $.ajaxSetup({
       // Disable caching of AJAX responses
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
	                   .attr('href', '/post/' + post.id)
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
/*
      <div style="margin-bottom: 25px; border: solid thin black;">
         <a href="/post/{{ post.getId() }}">{{ post.getTitle() }}</a><br />
         <br />
         Posted by: {{ post.getAuthor().getName() }} at {{ post.getDate()|date("Y/m/d h:i:s") }}
         <br /><br />
         Tags:
         {% for tag in post.getTags() %}
         {% if loop.last %}
         {{ tag.tag }}
         {% else %}
         {{ tag.tag }},
         {% endif %}
         {% endfor %}
      </div>
*/
});
