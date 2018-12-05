<div style="position: absolute; top: -1236px; overflow: auto; width:1241px;">
<?php
if ($_SERVER['SERVER_NAME'] == $first_domain):
?>
<h1>Nhịp sinh học là gì?</h1>
<p>
Nhịp sinh học (tiếng Anh: biorhythm) là một chu trình giả thiết về tình trạng khỏe mạnh hay năng lực sinh lý, cảm xúc, hoặc trí thông minh. Một nghiên cứu ở Nhật Bản trên công ty giao thông Ohmi Railway cũng đã lập các biểu đồ sinh học cho các tài xế lái xe của công ty để họ có sự cảnh giác và phòng tránh. Kết quả tai nạn của các tài xế đã giảm 50% từ năm 1969 đến 1970 tại Tokyo.
</p>
<h2>Cách tính Nhịp sinh học</h2>
<p>Do có chu trình đều và lặp lại, với mốc thời gian là ngày sinh, hoàn toàn dễ hiểu với các hàm số sau:</p>
<ul>
    <li>Sức khỏe: sin(2π t/23)</li>
    <li>Tình cảm: sin(2π t/28)</li>
    <li>Trí tuệ: sin(2π t/33)</li>
</ul>
<p>Với t là thời gian tính từ khi người đó được sinh ra.</p>
<?php
elseif ($_SERVER['SERVER_NAME'] == $second_domain):
?>
<h1>What is Biorhythm?</h1>
<p>
A biorhythm (from Greek βίος - bios, "life" and ῥυθμός - rhuthmos, "any regular recurring motion, rhythm") is an attempt to predict various aspects of a person's life through simple mathematical cycles. Most scientists believe that the idea has no more predictive power than chance and consider the concept an example of pseudoscience.
</p>
<h2>How to calculate Biorhythm</h2>
<p>The equations for the cycles are:</p>
<ul>
    <li>Physical: sin(2π t/23)</li>
    <li>Emotional: sin(2π t/28)</li>
    <li>Intellectual: sin(2π t/33)</li>
</ul>
<p>Where t indicates the number of days since birth.</p>
<p>Basic arithmetic shows that the simpler 23- and 28-day cycles repeats every 644 days (or 1-3/4 years), while the triple 23-, 28-, and 33-day cycles repeats every 21,252 days (or 58.2+ years).</p>
<?php
endif;
?>
</div>