<?php

// Load data from json file
$accounts = json_decode(file_get_contents("accounts.json"), true);

array_multisort(array_column($accounts, 'name'), SORT_NATURAL | SORT_FLAG_CASE, $accounts);

$rows = array();

// Generate markdown representation
foreach ($accounts as $account) {
	$name = $account["name"];

	$twitter = $account["twitter"];
	$twitter = "[@$twitter](https://twitter.com/$twitter)";

	$mastodon = $account["mastodon"];
	$mastodon_parts = explode("@", $mastodon);
	$mastodon = "[@$mastodon](https://".$mastodon_parts[1]."/@".$mastodon_parts[0].")";

	$rows[] = "|$name|$twitter|$mastodon|";
}

$out = "|Name|Twitter|Mastodon|\n";
$out .= "|-|-|-|\n";
$out .= join("\n", $rows);

// Append to README
$readme = file_get_contents("README.tpl.md");
$readme = str_replace("{ACCOUNT_TABLE}", $out, $readme);
$readme = str_replace("{NUM_ACCOUNTS}", count($accounts), $readme);
$res = file_put_contents("README.md", $readme);

echo '<a href="README.md">README.md</a>';
