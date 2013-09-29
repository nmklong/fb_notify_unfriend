<h1>Welcome welcome</h1>

- You have <?= $new_fl ?> friends in the current list

<?if(count($removed_list) > 0) {?>
<h3 style='margin-top:20px;'>Removed you:</h3>
<ul style='list-style-type:none;'>
    <?foreach($removed_list as $r) {?>
    <li style='margin-bottom:10px;'>
        <img src="https://graph.facebook.com/<?=$r["id"]?>/picture"/> <a target=_blank href='http://www.facebook.com/profile.php?id=<?=$r["id"]?>'><?= $r['name'] ?></a>
    </li>
    <?}?>
</ul>
<?}?>
