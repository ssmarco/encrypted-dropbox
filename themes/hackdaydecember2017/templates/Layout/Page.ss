<div class="jumbotron">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <% if not CurrentMember %>
                    not logged in
                <% else %>
                    <% include EncryptedDropbox %>
                <% end_if %>
            </div>
        </div>
    </div>
</div>
$Form
