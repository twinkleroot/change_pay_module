<?
$code = isset($result['code']) ? $result['code'] : 0;
$msg = isset($result['msg']) ? $result['msg'] : '';
$locationPath = $basePath != '' ? $basePath : "/pay.html";  // 결제버튼 클릭하는 페이지

// PG 랜더링 하기 전 에러 상황일 때 ajax 리턴으로 에러 얼럿을 띄워야 한다.
if ($code > 300) {
    echo json_encode($result);
} else {
?>

<script>
    alert("<?= $msg ?>");
    if (window.opener && !window.opener.closed) {
        window.opener.location = "<?= $locationPath ?>";
        window.close();
    }
</script>
<?
}
?>