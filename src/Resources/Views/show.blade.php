<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>msg-swoole客户端演示</title>

    <script type="text/javascript">
        function WebSocketTest($uid)
        {
            if ("WebSocket" in window)
            {
                alert("您的浏览器支持 WebSocket!");

                // 打开一个 web socket
                var ws = new WebSocket(document.getElementById('url').value);

                ws.onopen = function()
                {
                    // Web Socket 已连接上，使用 send() 方法发送数据
                    ws.send($uid);
                    alert("数据发送中...");
                };

                ws.onmessage = function (evt)
                {
                    var received_msg = evt.data;
                    alert("数据已接收...:"+received_msg);
                };

                ws.onclose = function()
                {
                    ws.send($uid);
                    // 关闭 websocket
                    alert("连接已关闭...");
                };
            }

            else
            {
                // 浏览器不支持 WebSocket
                alert("您的浏览器不支持 WebSocket!");
            }
        }
    </script>

</head>
<body>

<div id="sse">
    测试服务器地址<input type="text" value="ws://192.168.10.69:9501" id="url" size="300"/> <br/>
    <a href="javascript:WebSocketTest('{{$ids[0]}}')">运行用户1 WebSocket</a><br/>
    <a href="javascript:WebSocketTest('{{$ids[1]}}')">运行用户2 WebSocket</a><br/>
    <a href="javascript:WebSocketTest('{{$ids[2]}}')">运行用户3 WebSocket</a>
</div>

</body>
</html>
