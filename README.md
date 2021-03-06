<html>
<head>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQu
   mfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

</head>
<body>
<div id='doc-content'>
					<div class='api-content'>
						<h1>UNIWeb API</h1>
						
					<section id="introduction" >
						<h2>Overview</h2><p>The purpose of the API is to integrate UNIWeb with other systems within your organization. The Authenticated API provides secure read/write access to information stored by UNIWeb, and it provides a mechanism to reduce the need to duplicate data.</p><p>The UNIWeb API provides:</p><ul class="disc-list"><li>An interface that allows you to control who has access to your institution's data through our API.</li><li>A means by which to securely read and update your institution's information.</li><li>Rich data in simple, straightforward JSON for maximum readability and reusability.</li><li>The choice to pre-filter the requested data, to obtain just the subset of information in which you are interested.</li></ul><p></p><p>The UNIWeb API uses <a href="http://www.ietf.org/" target="_blank">Internet Engineering Task Force (IETF)</a> open authentication standard <a href="http://oauth.net/2/" target="_blank">OAuth 2.0</a>, for authentication and authorization using the <a href="http://tools.ietf.org/html/rfc6749#section-4.3" target="_blank">Resource Owner Password Credentials Grant protocol</a>. In this protocol, only users with granted permission can access the API.</p><p>The following four steps are required to access a resource through API:</p><ol class="num-list"><li>Get permission to create OAuth 2.0 Clients</li><li>Create a OAuth 2.0 client and obtain client credentials</li><li>Use your Oauth 2.0 client credentials to <a href="http://tools.ietf.org/html/rfc6749#section-4.3.2" target="_blank">retrieve an access token</a>.</li><li><a href="http://tools.ietf.org/html/rfc6749#section-7" target="_blank">Use your access token</a> to interact with the API. Your token is valid for an hour from the time it is issued.</li></ol><p>These steps are explained in more details below.</p><h3>Setting up Authorized Clients</h3><h4>1. Get permission to create OAuth 2.0 Clients</h4><div class="indent"><p>A <i>System Administrator</i>, can grant any user permission to <i>create OAuth 2.0 clients</i>. If you are not the <i>System Administrator</i> yourself, ask the <i>System Administrator</i> to give you this permission, as example below shows: </p><img src="http://uniweb.network/clients/Proximify/uniweb/published/img/apiImgs/admin_rbac.png" id="rbac"/><p>Example above shows <i>Access Control</i> page, accessible from <i>Administration</i> panel, to <i>John Doe</i>, the <i>System Administrator</i>. In this example, role <i>Health Science IT Administrator</i> has the permission to <i>create OAuth 2.0 clients</i> for <i>Health Sciences</i> department. <i>John Doe</i> assigns this role to <i>Jane Roe</i>. <i>Jane Roe</i> can now <i>create OAuth 2.0 clients</i>.</p></div><h4>2. Create a OAuth 2.0 client and obtain client credentials</h4><div class="indent"><p>Using the UNIWeb Interface, you can create, edit, view and remove OAuth 2.0 clients. Each client has a unique username referred to as <i>Client ID</i>, and a system generated random password, referred to as <i>Client Secret</i>. Example below shows <i>Jane Roes</i>'s <i>OAuth 2.0 Administration</i> page.</p><img src="http://uniweb.network/clients/Proximify/uniweb/published/img/apiImgs/api_oauth.png" id="oauth"></img><p>In this example, <i>Jane Roe</i> has created two OAuth 2.0 clients. Clicking on the <i>view</i> button for <i>Alice</i> reveals her <i>Client Secret</i> as shown below:</p><img src="http://uniweb.network/clients/Proximify/uniweb/published/img/apiImgs/api_oauth-view_client.png" id="oauth-view"></img><p>In this hypothetical case, <i>Alice</i>'s <i>Client ID</i> is <i>Alice</i> and her <i>Client Secret</i> is <i>7740731b32440350fccd</i>. These credentials are used in the next step to authenticate <i>Alice</i>.</p></div><h4>3. Authenticate and get an Access Token</h4><div class="indent"><p>With the client credentials obtained in step 3, you can authenticate to the UNIWeb <i>Token Endpoint</i>, and get an <i>Access Token</i>. An <i>Access Token</i> is valid for one hour, and it will be used in the next step to retrieve resources from UNIWeb <i>Resource Endpoint</i>.</p><p>With these pieces of information you will be allowed to make API requests. To do so, you can use one of our pre-built client libraries</p><ul><li><a href="https://github.com/Proximify/uniweb-api/tree/master/clients/PHP">PHP client lib</a> (see <a href="https://github.com/Proximify/uniweb-api/tree/master/clients/PHP/examples">examples</a>)</li></ul></div><h4>4. Access information through structured requests</h4><div class="indent"><p>API requests are made by submitting JSON objects to the server. They tell the server which action, resources, sections and fields are desired and what filters to apply. In particular, the request objects can have the following properties: <strong>action</strong>, <strong>content</strong>, <strong>filter</strong>, and <strong>resource</strong>.</p><p>Example request object:</p></div><pre class="Widget JsonCode prettyprint prettyprinted"><span class="pln">{</span>
    <span class="pln">"action":</span> <span class="str">"read"</span><span class="pln">,</span>
    <span class="pln">"content":</span> <span class="str">"members"</span><span class="pln">,</span>
    <span class="pln">"filter":</span> <span class="pln">{</span>
        <span class="pln">"login":</span> <span class="str">"bob@mail.ca"</span>
    <span class="pln">}</span><span class="pln">,</span>
    <span class="pln">"resource":</span> <span class="pln">[</span>
        <span class="str">"profile/biography"</span><span class="pln">,</span>
        <span class="str">"profile/selected_degrees"</span>
    <span class="pln">]</span>
<span class="pln">}</span></pre></br><p class="inent">Example response for the above request: </p><pre class="Widget JsonCode prettyprint prettyprinted"><span class="pln">{</span>
    <span class="pln">"bob@mail.ca":</span> <span class="pln">{</span>
        <span class="pln">"profile/biography":</span> <span class="str">"Bob always knew he would be a great scientist"</span><span class="pln">,</span>
        <span class="pln">"profile/selected_degrees":</span> <span class="pln">[</span>
            <span class="pln">{</span>
                <span class="pln">"degree_name":</span> <span class="str">"PhD"</span><span class="pln">,</span>
                <span class="pln">"organization":</span> <span class="str">"McGill University"</span><span class="pln">,</span>
                <span class="pln">"specialty":</span> <span class="str">"Materials Engineering"</span>
            <span class="pln">}</span><span class="pln">,</span>
            <span class="pln">{</span>
                <span class="pln">"degree_name":</span> <span class="str">"Engineering"</span><span class="pln">,</span>
                <span class="pln">"organization":</span> <span class="str">"University of Ottawa"</span>
            <span class="pln">}</span>
        <span class="pln">]</span>
    <span class="pln">}</span>
<span class="pln">}</span></pre>
					</section>
				
					<section id="requests" >
						<h2>API Requests</h2><p>Before requesting information from UNIWeb, it is necessary to understand the terminology used to identify pieces of data stored in the system. The information within a UNIWeb page is usually divided into sections, sub-sections, sub-subsections and so on. A <em>section</em> contains a list of <em>items</em>. An item within a section is made out <em>fields</em>. An API <em>request</em> is the mechanism for obtaining the <em>field values</em> of all items within a section.</p><h3 id="resource_paths">Resource Paths</h3><p>In UNIWeb, a resource is always associated to a type of <em>content</em>. Current content types are: 'members', 'units' and 'groups'.</p><p>To request a resource, it is necessary to provide a path to it within UNIWeb. A <em>request path</em> can be specified as a string by separating each element in the path with '/'. The path must have the following form:</p></br><p class="centered"><code>page/section/section/section/...</code></p></br><div class="indent"><table class="Widget Table api-actions"><tbody><tr><td><code>page</code><div id="action-options" class="Togglable"></div></td><td>The 'page' where the information is displayed within UNIWeb. For example, 'profile', 'cv' or 'graph'.<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>section/...</code><div id="action-options" class="Togglable"></div></td><td>Sequence of section, subsection, sub-subsection,... that contain the target set of items to retrieve.<div id="action-options" class="Togglable"></div></td></tr></tbody></table></div><span>For example, the string </span><span><pre class="InlineCode">cv/education/degrees</pre> refers to all the items within the section <em>Degrees</em>, which is a subsection of the <em>Education</em> section in the CV page of UNIWeb <em>members</em>.</span><p>Optionally, a request path can be specified as a JSON object. In particular, this is needed if one desires to request only a subset of the field values of an item. In this case, the <em>resource path</em> can be given as </p><pre>   {"page/section/section/section/...": ["field name A", "field name B", ...]}</pre><p></p><p>It is also possible to encode the entire path as a JSON object. This is useful when requesting multiple sections under a common parent section or page:</p><pre>   "page":{
       "parent_section":[
         "child_section A",
         "child_section B"
       ]
   }</pre><p>The <em>resource path</em> above is equivalent to specifying two separate resource paths as strings:</p><pre>   [
       "page/parent_section/child_section A",
       "page/parent_section/child_section B"
   ]</pre><p></p><h3>Naming Conventions</h3><p>The names of sections and fields used by the API are derived from the <strong>English titles</strong> of their respective sections and fields shown in the UNIWeb UI. Spaces, slashes and question marks are not allowed in resource names. In addition, resource names are always lowercased. To "normalize" a string to meet API rules, do the following:</p><ol class="disc-list"><li>Lowercase the given string</li><li>Replace the substrings " / ", "/", and " " with "_"</li><li>Replace the substrings "?" with the empty string ""</li></ol><p>For example, the string "Postal / Zip Code" is normalized to "postal_zip_code".</p><p></p><h3>Section Names</h3><p>The names of sections in resource paths must: (1) correspond to the sections names shown in the UNIWeb UI, and (2) be normalized according to the API naming rules described above. For example, the path to the Address resource in a CV is written as </p><p><code>cv/personal_information/address</code></p><img src="http://uniweb.network/clients/Proximify/uniweb/published/img/apiImgs/api_cv_sections.png" id="api_cv_sections"></img><h3></h3><p>The names of fields in resource paths must: (1) correspond to the field labels in the UNIWeb UI, and (2) be normalized according to the API naming rules described above. For example, the fields shown below in the Address section can be requested as </p><code>["address_type", "address_-_line_1", "location", "postal_zip_code"]</code><p></p><img src="http://uniweb.network/clients/Proximify/uniweb/published/img/apiImgs/api_fields.png" id="api_fields"></img></br></br></br><h3>Stucturing Requests</h3><p>API requests are given as JSON objects with one or more of the following properties.</p><div class="indent"><table class="Widget Table api-actions"><tbody><tr><td><code>token</code><div id="action-options" class="Togglable"></div></td><td><span class="Label Mandatory">required</span> The hashed value returned by the authorization server.<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>action</code><div id="action-options" class="Togglable"></div></td><td><span class="Label Mandatory">required</span> A string value specifying the desired action to take.<div id="action-options" class="Togglable"><table class="Widget Table tight"><tbody>


   <tr><td><code>read</code><div id="action-options" class="Togglable"></div></td><td>returns the JSON representation for a requested set of resources<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>add</code><div id="action-options" class="Togglable"></div></td><td>creates a new item for entry into the database<div id="action-options" class="Togglable"></div></td></tr>

   <tr><td><code>edit</code><div id="action-options" class="Togglable"></div></td><td>edits a section field<div id="action-options" class="Togglable"></div></td></tr>

   <tr><td><code>options</code><div id="action-options" class="Togglable"></div></td><td>returns a list of options possible to obtain additional system information<div id="action-options" class="Togglable"></div></td></tr>

  <tr><td><code>clear</code><div id="action-options" class="Togglable"></div></td><td>clears section data<div id="action-options" class="Togglable"></div></td></tr>

  <tr><td><code>info</code><div id="action-options" class="Togglable"></div></td><td>retrieves section info<div id="action-options" class="Togglable"></div></td></tr>

  <tr><td><code>getMembers</code><div id="action-options" class="Togglable"></div></td><td>returns list of members <div id="action-options" class="Togglable"></div></td></tr>



  <tr><td><code>getTitles</code><div id="action-options" class="Togglable"></div></td><td>returns list of title names <div id="action-options" class="Togglable"></div></td></tr>



  <tr><td><code>getUnits</code><div id="action-options" class="Togglable"></div></td><td>returns list of units and their parents  <div id="action-options" class="Togglable"></div></td></tr>

  <tr><td><code>getPermissions</code><div id="action-options" class="Togglable"></div></td><td> returns list of RBAC roles <div id="action-options" class="Togglable"></div></td></tr>

  <tr><td><code>getRolesPermissions</code><div id="action-options" class="Togglable"></div></td><td> returns list of roles permissions <div id="action-options" class="Togglable"></div></td></tr>

  <tr><td><code>getSections</code><div id="action-options" class="Togglable"></div></td><td> returns list of sections. <div id="action-options" class="Togglable"></div></td></tr>


  <tr><td><code>getFields</code><div id="action-options" class="Togglable"></div></td><td> returns list of fields. <div id="action-options" class="Togglable"></div></td></tr>

   </tbody></table></div></td></tr>


   <tr><td><code>content</code><div id="action-options" class="Togglable"></div></td><td><span class="Label Mandatory">required</span> Selects the type of content to retrieve.<a class="Toggler" onclick="$(this).hide().next().show().next().show();">Show options</a><a class="Toggler Togglable" onclick="$(this).hide().next().hide(); $(this).prev().show();">Hide options</a><div id="action-options" class="Togglable"><table class="Widget Table tight"><tbody><tr><td><code>members</code><div id="action-options" class="Togglable"></div></td><td>refers to the departmental unit, such as 'Engineering'<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>units</code><div id="action-options" class="Togglable"></div></td><td>refers to your institution listing of possible titles, such as 'Professor'<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>groups</code><div id="action-options" class="Togglable"></div></td><td>may be either a UNIWeb username, login email address or UNIWeb ID.  This represents the most user-specific filter option<div id="action-options" class="Togglable"></div></td></tr></tbody></table></div></td></tr><tr><td><code>resource</code><div id="action-options" class="Togglable"></div></td><td><span class="Label Mandatory">required</span> One or more paths to the requested resources.<a class="Toggler" onclick="$(this).hide().next().show().next().show();">Show options</a><a class="Toggler Togglable" onclick="$(this).hide().next().hide(); $(this).prev().show();">Hide options</a><div id="action-options" class="Togglable"><table class="Widget Table tight"><tbody><tr><td><p>The value of this property can be a string, an object or an array of strings/objects. The format and naming conventions of resource paths are described in the section <a href="#resource_paths">Resource Paths</a> above.</p><div id="action-options" class="Togglable"></div></td></tr></tbody></table></div></td></tr><tr><td><code>filter</code><div id="action-options" class="Togglable"></div></td><td><span class="Label Optional">optional</span> An object value with filering settings.<a class="Toggler" onclick="$(this).hide().next().show().next().show();">Show options</a><a class="Toggler Togglable" onclick="$(this).hide().next().hide(); $(this).prev().show();">Hide options</a><div id="action-options" class="Togglable"><table class="Widget Table tight"><tbody><tr><td><code>unit</code><div id="action-options" class="Togglable"></div></td><td>refers to the departmental unit, such as 'Engineering'<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>title</code><div id="action-options" class="Togglable"></div></td><td>refers to your institution listing of possible titles, such as 'Professor'<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>login</code><div id="action-options" class="Togglable"></div></td><td>may be either a UNIWeb username, login email address or UNIWeb ID.  This represents the most user-specific filter option<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>modified_since</code><div id="action-options" class="Togglable"></div></td><td>A TIMESTAMP in the range '1970-01-01 00:00:01' UTC to '2038-01-19 03:14:07' UTC. Only items modified on or after the given date are returned.<div id="action-options" class="Togglable"></div></td></tr></tbody></table></div></td></tr><tr><td><code>index_by</code><div id="action-options" class="Togglable"></div></td><td><span class="Label Optional">optional</span> Selects how the response indexed the resources in the answer.<a class="Toggler" onclick="$(this).hide().next().show().next().show();">Show options</a><a class="Toggler Togglable" onclick="$(this).hide().next().hide(); $(this).prev().show();">Hide options</a><div id="action-options" class="Togglable"><table class="Widget Table tight"><tbody><tr><td><code>uniweb_id</code><div id="action-options" class="Togglable"></div></td><td>The internal ID of each resource is used [the default].<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>login_name</code><div id="action-options" class="Togglable"></div></td><td>The login name is used [only if <code>content</code> is 'members'].<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>employee_id</code><div id="action-options" class="Togglable"></div></td><td>The institutions employee ID  is used [only if <code>content</code> is 'members'].<div id="action-options" class="Togglable"></div></td></tr></tbody></table></div></td></tr><tr><td><code>language</code><div id="action-options" class="Togglable"></div></td><td><span class="Label Optional">optional</span> Responses use the default institution's language unless specified otherwise.<a class="Toggler" onclick="$(this).hide().next().show().next().show();">Show options</a><a class="Toggler Togglable" onclick="$(this).hide().next().hide(); $(this).prev().show();">Hide options</a><div id="action-options" class="Togglable"><table class="Widget Table tight"><tbody><tr><td><code>fr</code><div id="action-options" class="Togglable"></div></td><td>French is used for the response.<div id="action-options" class="Togglable"></div></td></tr><tr><td><code>en</code><div id="action-options" class="Togglable"></div></td><td>English is used for the response.<div id="action-options" class="Togglable"></div></td></tr></tbody></table></div></td></tr></tbody></table></div></br>
					</section>
				
					<section id="examples" >
						<h2>Example Requests</h2><h4>Simple Single Resource Read Request</h4><p>The request that follows would return the public profile information of all people in the Department of Civil Engineering as JSON.</p><pre class="Widget JsonCode prettyprint prettyprinted"><span class="pln">{</span>
    <span class="pln">"token":</span> <span class="str">"access token"</span><span class="pln">,</span>
    <span class="pln">"action":</span> <span class="str">"read"</span><span class="pln">,</span>
    <span class="pln">"content":</span> <span class="str">"members"</span><span class="pln">,</span>
    <span class="pln">"filter":</span> <span class="pln">{</span>
        <span class="pln">"unit":</span> <span class="str">"Civil Engineering"</span>
    <span class="pln">}</span><span class="pln">,</span>
    <span class="pln">"resource":</span> <span class="str">"profile"</span>
<span class="pln">}</span></pre><h4>Requesting to Read Multiple Resources in a Single Request</h4><p>The request that follows would return two resources belonging to the user with login name <code>john@smith.ca</code>, which include:</p><ol class="disc-list"><li>the publicly available research interest tags found on his Profile</li><li>the Degree Name, Specialization, and Thesis Title fields from his CV found under Education > Degrees</li></ol><p></p><pre class="Widget JsonCode prettyprint prettyprinted"><span class="pln">{</span>
    <span class="pln">"token":</span> <span class="str">"access token"</span><span class="pln">,</span>
    <span class="pln">"action":</span> <span class="str">"read"</span><span class="pln">,</span>
    <span class="pln">"content":</span> <span class="str">"members"</span><span class="pln">,</span>
    <span class="pln">"language":</span> <span class="str">"fr"</span><span class="pln">,</span>
    <span class="pln">"filter":</span> <span class="pln">{</span>
        <span class="pln">"unit":</span> <span class="str">"McGill"</span><span class="pln">,</span>
        <span class="pln">"title":</span> <span class="str">"Professor"</span><span class="pln">,</span>
        <span class="pln">"login":</span> <span class="str">"john@smith.ca"</span>
    <span class="pln">}</span><span class="pln">,</span>
    <span class="pln">"resource":</span> <span class="pln">[</span>
        <span class="str">"profile/research_interests"</span><span class="pln">,</span>
        <span class="pln">{</span>
            <span class="pln">"cv/education/degrees":</span> <span class="pln">[</span>
                <span class="str">"degree_name"</span><span class="pln">,</span>
                <span class="str">"specialization"</span><span class="pln">,</span>
                <span class="str">"thesis_title"</span>
            <span class="pln">]</span>
        <span class="pln">}</span>
    <span class="pln">]</span>
<span class="pln">}</span></pre>
					</section>
				
					<section id="errors" >
						<h2>Error Messages</h2><p>Errors will give information about what went wrong with a corresponding request. They will be of the following form:</p><pre class="Widget JsonCode prettyprint prettyprinted"><span class="pln">{</span>
    <span class="pln">"error":</span> <span class="pln">{</span>
        <span class="pln">"message":</span> <span class="str">"Error validating access token."</span><span class="pln">,</span>
        <span class="pln">"type":</span> <span class="str">"OAuthException"</span><span class="pln">,</span>
        <span class="pln">"code":</span> <span class="lit">98</span><span class="pln">,</span>
        <span class="pln">"error_subcode":</span> <span class="lit">223</span>
    <span class="pln">}</span>
<span class="pln">}</span></pre></br>
					</section>
				
					</div>
</body>
</html>
