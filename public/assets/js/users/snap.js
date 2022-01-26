// レシート撮影

(function(){

	// 変数セット
	var str = "";
	var pay_date = "";
	var tel = ""
	var no = "";
	var time = ""
	var items = Array();
	var isChangeValue = false;
	var term = 0;
	var re_point = '';
	var re_oil = '';
	var com = '';
	var isNg = false;
	var isShopNg = false;
	var double = false;
	var isLightNg = false;
	var isAcceptAll = false;
	var productNg = "0";
	var startTime = $("#start_time").val()-0;
	var endTime = $("#end_time").val()-0;
	var tel_blank = false;

	function reset(){
		$("#res_input").val("");
		$("#get_point").text("");
		$("#error_reason").empty();
		str = "";
		tel = "";
		pay_date = "";
		no = "";
		isChangeValue = false;
		isNg = false;
		isLightNg = false;
		items = Array();
		isAcceptAll = false;
	}

	function checkStatus(){

		if(isAcceptAll){
			$("#snap_ok").show();
			return
		}

		var isError = false;
		if($("#res_input").val() == "0"){
			$("#error_reason").append("<p>最低給油量に達していません。</p>");
			alert('最低給油量に達していません。');
			isError = true;
		}

		if(items.length == 0){
			$("#error_reason").append("<p>対象商品がありません。</p>");
			alert('対象商品がありません。');
			productNg = "1";
			isError = true;
		}

		/*if(tel == ""){
			$("#error_reason").append("<p>対象店舗を検出できません。</p>");
			isError = true;
		}*/

		// 一時的にコメントアウト
		if(pay_date != ""){
			var dateInt = pay_date.replace(/[^0-9]/g, '')-0;
			if(dateInt < startTime || dateInt > endTime){
				// $("#error_reason").append("<p>対象期間のレシートですか？</p>");
				// isError = true;
				term = 1;
			} else {
				term = 0;
			}
		}
		
		if(isError){
			$("#error_reason").append("<p>レシート撮影時の注意事項を確認の上、再度送信してください。</p>");
			$("#snap_error").show();
		}
		else $("#snap_ok").show();
	}

	// 送信ボタン
	$("#sendBtn").on("click",function(){

		// NGワード
		if(isNg == true) {
			alert('対象外のレシートです。');
		}
		
		// 電話番号確認
		// var tel_last = tel.slice( -4 ) ;
		// var tel_first = tel.slice( 0,1 ) ;
		// var tel_length = tel.length;
		// if(tel_last == "0000" || tel_last == "-000" || tel_length !== 12 || tel_first !== "0") {
		// 	tel_blank = true;
		// }

		// 獲得ポイントが0
		var point_input = $('.point_input').html();
		if(point_input == "0") {
			alert('最低給油量に達していません');
			return
		}

		// 給油量の自己申告
		if($("#res_input").val() == "" || isNaN($("#res_input").val())){
			alert("数量が読み取れません。");
			return
		}

		// 給油量が数字以外
		var oil_check = $('#res_input').val();
		if(oil_check.match(/^([1-9]\d*|0)(\.\d+)?$/)) {
		} else {
			alert("数量が読み取れません。");
			return
		}

		// 給油量25Lに対して
		var point = Math.floor($("#res_input").val()/25);
		var po = po;

		// 送信データ成形
		tel = tel.replace(/-/g, '');
		tel = tel.replace(/-/g, '');
		var blob = image2blob($("#camImage").get(),true);
		var data = new FormData();
		data.append("image", blob);
		data.append("val",$("#res_input").val());
		data.append("user_id",$("#user_id").val());
		data.append("campaign_id",$("#cam_id").val());
		data.append("start_time",$("#start_time").val());
		data.append("end_time",$("#end_time").val());
		data.append("campaign_shop_tree",$("#campaign_shop_tree").val());
		data.append("pay_date",pay_date.replace(/[年月]/g, '-').replace("日",""));
		data.append("tel",tel);
		data.append("str",str);
		data.append("no",no);
		data.append("term",term);
		data.append("re_point",re_point);
		data.append("re_oil",re_oil);
		data.append("point",point);
		data.append("productNg",productNg);
		data.append("telBlank",tel_blank);
		data.append("com",com);

		data.append("time",time);
		if(isAcceptAll)data.append("accept",1);
		else data.append("accept",0);

		if(isChangeValue)data.append("isChange",1);
		else data.append("isChange",0);

		if(isNg)data.append("isNg",1);
		else data.append("isNg",0);

		if(isLightNg)data.append("isLightNg",1);
		else data.append("isLightNg",0);

		if(isShopNg)data.append("isShopNg",1);
		else data.append("isShopNg",0);

		if(double)data.append("double",1);
		else data.append("double",0);

		var itemsStr = "";
		if(items.length > 0){
			for(var n=0;n<items.length;n++){
				if(n != 0){
					itemsStr += "&I";
				}
				itemsStr += items[n][0]+"&C"+items[n][1];
			}
		}
		data.append("products",itemsStr);
	
		// Ajax post
		$data ={
			url: '/assets/ajax/post_receipt.php',
			type: 'POST',
			data:data,
			contentType: false,
			processData: false
		};
		callFrontAjax($data, function(data){
			if(data.result){
				alert("送信が完了しました。");
			}
			else{
				//$("#snap_caution").show();
        		//$("#snap_error").hide();
        		$("#snap_ok").hide();
    			$("#snap_result").show();
				$("#fileInput").val("");
				$("#re_fileInput").val("");
				reset();

				$("#snap_error").show();
				$("#error_reason").append("<p>"+data.reason+"</p>");
				if(data.isShopNg){
					isShopNg = true;
				}
				if(data.double){
					double = true;
				}
				//return;
				alert(data.reason);
				return;
			}
			// 再送信用初期化
			$("#snap_caution").show();
        	$("#snap_error").hide();
        	$("#snap_ok").hide();
    		$("#snap_result").hide();
			$("#fileInput").val("");
			$("#re_fileInput").val("");

			reset();
		});
	});

	// 照合対照の変数セット
	var searchList = [[/\d{2,4}-\d{2,4}-\d{4}/gi,"tel"]
					,[/(20\d{2})年(\d{2})月(\d{2})./,"日付"]
					,[/(\d{2}):(\d{2})/,"時間"]
					,[/レシートN[O0o]\d{2,6}-\d{2,6}/,"レシートNo"]];
	var itemList = [["軽油",/*"FP-04"*/"0216-00","軽数量油"]
					/*,["アド·ブルー","P62"]
					,["レギュラー"]
					,["ハイオク"]*/
					];
	var searchCnt = [/\d{1,3}.\d{2}[L]/]
	var ngList = ["SS控","SSEえ","サイン","取消","仕入","実在庫"];
	var light_ngList = ["0再","1再","2再","3再","4再","5再","6再","7再","8再","9再"];

	//alert("0532-63-8116".match("/\d{2,4}-\d{2,4}-\d{4}/gi"));

	// フロント獲得ポイント計算
	// $("#res_input").change(function(){
	// 	isChangeValue = true;
	// 	var po = Math.floor($(this).val()/25);
	// 	$("#get_point").text(po+" <span>pts</span>");
	// });
	$(function() {
		var $input = $('#res_input');
		$input.on('input', function(event) {
			isChangeValue = true;
			var po = Math.floor($(this).val()/25);
			$("#get_point").html('<span class="point_input">'+po+'</span>' + ' pts');
		});
	});

	$("#image_area").hide();

	// 撮り直し場合
	$("#re_fileInput").change(function(e){
		reset();
        sendImage(this);
    });

	//エラーによる取り直し
	$("#error_fileInput").change(function(e){
		reset();
		isAcceptAll = true;
        sendImage(this,true);
    });

	// 撮影後
	$("#fileInput").change(function(e){
		sendImage(this);
	});
	function sendImage(input,isAccept){
		$("#snap_caution").hide();
        $("#snap_error").hide();
        $("#snap_ok").hide();
        $("#snap_result").show();
        $("#sub_title").text("読み取り内容の確認");
		$("#snap_ok").find("#debug").remove();

		// overlay表示
		if(!isAccept)$('.overlay').show();

        console.log(input.files[0]);
		var file = input.files[0];

		// 画像データ読み取り後の処理
		var reader = new FileReader();
        reader.onload = function(){
			$("#camBtn").css("margin-top","5px");
			$("#camImage").unbind('load');
			$("#camImage").bind('load', function(){
				var base64 = image2base64($("#camImage").get(),true);
				
				if(isAccept){
					checkStatus();
					return;
				}
				
				var noHeader = base64.replace(/^data:image\/(png|jpg|jpeg);base64,/, "")
				var data = {
					"requests":[
						{
						"image":{
							"content":noHeader
						},
						"features":[
							{
							"type":"TEXT_DETECTION",
							"maxResults":3
							}
						]
						}
					]
				}
				// APIへ
				$data ={
					url: "https://vision.googleapis.com/v1/images:annotate?key=AIzaSyDsJ6Gbmaww6_7zhh8Ttqga5o3dUzQV3lw",
					type: 'POST',
					data:JSON.stringify(data),
					contentType: 'application/json',
					dataType: "json",
					processData: false
				};
				// 返却データ成形 & 返却後フロント処理
				callFrontAjax($data, function(data){
					// overlay非表示
					$('.overlay').hide();
					//console.log(JSON.stringify(data));
					var array = new Array();
					$("#res").empty();
					var pages = data["responses"][0]["fullTextAnnotation"]["pages"];
					if(pages.length > 0){
						for(var n=0;n<pages.length;n++){
							var blocks = pages[n]["blocks"];
							if(blocks.length > 0){
								for(var i=0;i<blocks.length;i++){
									var paragraphs = blocks[i]["paragraphs"];
									if(paragraphs.length > 0){
										for(var m=0;m<paragraphs.length;m++){
											var words = paragraphs[m]["words"];
											if(words.length > 0){
												for(var k=0;k<words.length;k++){
													var symbols = words[k]["symbols"];
													if(symbols.length > 0){
														for(var o=0;o<symbols.length;o++){
															var x = symbols[o].boundingBox.vertices[0].x;
															var y = symbols[o].boundingBox.vertices[0].y;
															var text = symbols[o].text;
															array.push([x,y,text,symbols[o].boundingBox]);
															//$("#res").append("<p>"+text+","+x+","+y+"</p>");
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
					if(array.length > 0){
						array.sort(function(a,b){
							return a[1]-b[1];
						});

						var texts = [];

						var oldY = -1;
						var threshold = 10;
						var temp = new Array();
						var res = new Array();

						var throwList = ["-","ー",".",",",":"]; 

						for(var n=0;n<array.length;n++){
							threshold = (array[n][3].vertices[3].y-array[n][3].vertices[0].y)/2*1.4;
							
							var x = array[n][0];
							var y = array[n][3].vertices[0].y+(array[n][3].vertices[3].y-array[n][3].vertices[0].y)/2;//-threshold;

							/*if(array[n][2] == "-"){
								console.log("y:"+y+",oldY"+oldY+"th:"+threshold);
								console.log((oldY-threshold)+" <= "+y+" <= "+oldY+threshold);
							}*/

							if(oldY == -1 || throwList.indexOf(array[n][2]) > -1/*array[n][2] == "-" || array[n][2] == "."*/){
								oldY = y;
							}
							else if(oldY-threshold <= y && y <= oldY+threshold){
								oldY = y;
							}
							else{
								oldY = -1;
								temp.sort(function(a,b){
									return a[0]-b[0];
								});
								var text = "";
								for(var i=0;i<temp.length;i++){
									if(i != 0){
										var thx = (temp[i][3].vertices[1].x-temp[i][3].vertices[0].x);

										//console.log((temp[i-1][3].vertices[1].x+thx)+" > "+temp[i][3].vertices[1].x);
										console.log(throwList.indexOf(temp[i][2]));
										if(throwList.indexOf(temp[i][2]) != -1 && temp[i-1][3].vertices[1].x+thx > temp[i][3].vertices[1].x){
											text += " 　 　";
										}
									}
									console.log(temp[i][2]);
									text += temp[i][2];
								}
								res.push(temp);
								texts.push(text);
								temp = [];
							}
							temp.push(array[n]);
							
						}
						temp.sort(function(a,b){
							return a[0]-b[0];
						});
						var text = "";
						for(var n=0;n<temp.length;n++){
							text += temp[n][2];
						}
						res.push(temp);

					}
					console.log(res);

					var itemIndex = new Array();
					var params = ["","","",""];
					var itemListC = itemList

					for(var n=0;n<texts.length;n++){
						//$("#res").append("<p>"+texts[n]+"</p>");

						for(var i=0;i<itemListC.length;i++){
							for(var m=0;m<itemListC[i].length;m++){
								var flag = false
								var res = texts[n].indexOf(itemListC[i][m]);
								
								if(res != -1){
									var ary = Array();
									ary.push([itemListC[i][m]]);
									for(var k=n;k<n+2;k++){
										for(var j=0;j<searchCnt.length;j++){
											
											var res = searchCnt[j].exec(texts[k].replace(/[oO]/g, '0'));
											if(res != null){
												//var ary = Array();
												//ary.push([itemListC[i][m]]);
												ary.push(/\d{1,3}.\d{2}/.exec(texts[k].replace(/[oO]/g, '0'))[0]);
												//items.push(ary);
												flag = true;
												break;
											}
										}
										if(flag)break;
									}
									if(!flag)ary.push("");
									items.push(ary);
								}
								if(flag)break;
							}
						}

						for(var i=0;i<searchList.length;i++){
							var res = searchList[i][0].exec(texts[n]);
							if(res != null){
								console.log("res:"+res);
								if(i == 3){
									params[i] = /\d{2,6}-\d{2,6}/.exec(texts[n])[0];
								} 
								else{
									params[i] = res[0];
								}
								//$("#res").append("<p>"+searchList[i][1]+":"+res+"</p>");
							}
						}
						
						/*for(var i=0;i<list.length;i++){
							if(texts.indexOf(list[i])){
								itemIndex.push(i);
							}
						}*/
						var ng_search = texts.join("");
						$.each(ngList, function(index, value) {
							var ngword = value;
							if ( ng_search.indexOf(ngword) != -1) {
								// ["SS控","SSEえ","サイン","取消","仕入","実在庫"
								if(ngword == 'SS控') {
									ng1 = true;
								} else if(ngword == 'SSEえ') {
									ng1 = true;
								} else if(ngword == 'サイン') {
									ng2 = true;
								} else if(ngword == '取消') {
									ng3 = true;
								} else if(ngword == '仕入') {
									ng4 = true;
								} else if(ngword == '実在庫') {
									ng5 = true;
								}
								isNg = true;
								// alert('対象外のレシートです。');
							}
						})
						$.each(light_ngList, function(index, value) {
							var ngword = value;
							if ( ng_search.indexOf(ngword) != -1) {
								isLightNg = true;
							}
						})
						// for(var i=0;i<ngList.length;i++){
						// 	if(texts.indexOf(ngList[i]) != -1){
						// 		//alert(ngList[i]);
						// 		isNg = true;
						// 	}
						// }
					}

					
					

					var $res = $("<div id='debug' style='display:none;'><p>↓↓↓デバッグ↓↓↓</p></div>")
					$("#snap_ok").append($res);

					if(items.length > 0){
						for(var n=0;n<items.length;n++){
							$res.append("<p>"+items[n][0]+":"+items[n][1]+"</p>");
							if(items[n][1] != ""){
								$("#res_input").val(items[n][1]);
	
								var po = Math.floor(items[n][1]/25);
								re_point = po;
								re_oil = items[n][1];
								$("#get_point").html('<span class="point_input">'+po+'</span>' + ' pts');
							}
						}
						/*$("#res_input").val(items[0][1]);

						var po = Math.floor(items[0][1]/25);
						re_point = po;
						re_oil = items[0][1];
						$("#get_point").html('<span class="point_input">'+po+'</span>' + ' pts');*/
						
					}
					
					$res.append("</br>isAccept："+isAcceptAll+"</br>term："+term+"<br>");

					for(var i=0;i<params.length;i++){
						$res.append("<p>"+searchList[i][1]+":"+params[i]+"</p>");
					}

					$res.append("</br></br>");


					str = "";
					for(var n=0;n<texts.length;n++){
						$res.append("<p>"+texts[n]+"</p>");
						str += texts[n];
					}

					tel = params[0];
					pay_date = params[1];//+" "+params[2];
					time = params[2];
					no = params[3];

					checkStatus()

					// 会社名 #debug p
					$('#debug p').each(function(){
						var target = $(this).html();
						if (target.indexOf("会社") >= 0) {
							com = $(this).html();
							$('#com').val(com);
						}
						if (target.indexOf("㈱") >= 0) {
							com = $(this).html();
							$('#com').val(com);
						}
						if (target.indexOf("(株)") >= 0) {
							com = $(this).html();
							$('#com').val(com);
						}
					});

					//$("#res").append("<p>"+JSON.stringify(data)+"</p>");
				});
			});

            $("#camImage").attr("src",reader.result);
			$("#image_area").show();

			
        }
        reader.readAsDataURL(file);
    }

	// リサイズ
	function image2base64(img,isResize){

		var image = new Image();
		image.src = $(img).attr("src");
		var maxWidth = 3024.0;
		var maxHeight = 4032.0;
		//maxWidth /= 2;
		//maxHeight /= 2;
		var width,height;
				
		if(isResize && (image.width > maxWidth || image.height > maxHeight)){
			var ratio = image.width/image.height;
			if(image.width-maxWidth > image.height-maxHeight){
				width = maxWidth;
				height = maxWidth/ratio;
			}
			else{
				width = maxHeight*ratio;
				height = maxHeight;
			}
		}
		else{
			width = image.width;
			height = image.height;
		}
			
		var canvas = document.getElementById("resize_canvas");
		canvas.width = width;
		canvas.height = height;
		var context = canvas.getContext("2d");
		context.clearRect(0,0,width,height);
		context.drawImage(image,0,0,image.width,image.height,0,0,width,height);

		/*var imgData = context.getImageData(0,0,image.width,image.height);

		var data = imgData.data;
			
		var rRate=0.34;
		var gRate=0.33;
		var bRate=0.33;
		
		for(var n=0,len=imgData.width*imgData.height;n<len;n++){
			var r=data[n*4];
    		var g=data[n*4+1];
        	var b=data[n*4+2];        		
        		
        	data[n*4]=Math.round(rRate*r+gRate*g+bRate*b);
    		data[n*4+1]=Math.round(rRate*r+gRate*g+bRate*b);
    		data[n*4+2]=Math.round(rRate*r+gRate*g+bRate*b);
        }

		context.clearRect(0,0,width,height);
		context.putImageData(imgData,0,0);*/

		var base64 = canvas.toDataURL("image/jpeg", 0.5);
		return base64;


		/*var barr, bin, i, len;
		bin = atob(base64.split('base64,')[1]);
		len = bin.length;
		barr = new Uint8Array(len);
		i = 0;
		while (i < len) {
			barr[i] = bin.charCodeAt(i);
			i++;
		}
		blob = new Blob([barr], {type: "image/jpeg"});
		return blob;*/
	}

	// リサイズ
	function image2blob(img,isResize){

		var image = new Image();
		image.src = $(img).attr("src");
		var maxWidth = 3024.0/4;
		var maxHeight = 4032.0/4;
		//maxWidth /= 2;
		//maxHeight /= 2;

		var width,height;
				
		if(isResize && (image.width > maxWidth || image.height > maxHeight)){
			var ratio = image.width/image.height;
			if(image.width-maxWidth > image.height-maxHeight){
				width = maxWidth;
				height = maxWidth/ratio;
			}
			else{
				width = maxHeight*ratio;
				height = maxHeight;
			}
		}
		else{
			width = image.width;
			height = image.height;
		}
			

		var canvas = document.getElementById("resize_canvas");
		canvas.width = width;
		canvas.height = height;
		var context = canvas.getContext("2d");
		context.clearRect(0,0,width,height);
		context.drawImage(image,0,0,image.width,image.height,0,0,width,height);

		var imgData = context.getImageData(0,0,image.width,image.height);

		var data = imgData.data;
			
		var rRate=0.34;
		var gRate=0.33;
		var bRate=0.33;
		
		for(var n=0,len=imgData.width*imgData.height;n<len;n++){
			var r=data[n*4];
    		var g=data[n*4+1];
        	var b=data[n*4+2];        		
        		
        	data[n*4]=Math.round(rRate*r+gRate*g+bRate*b);
    		data[n*4+1]=Math.round(rRate*r+gRate*g+bRate*b);
    		data[n*4+2]=Math.round(rRate*r+gRate*g+bRate*b);
        }

		context.clearRect(0,0,width,height);
		context.putImageData(imgData,0,0);

		var base64 = canvas.toDataURL("image/jpeg", 0.5);

		var barr, bin, i, len;
		bin = atob(base64.split('base64,')[1]);
		len = bin.length;
		barr = new Uint8Array(len);
		i = 0;
		while (i < len) {
			barr[i] = bin.charCodeAt(i);
			i++;
		}
		blob = new Blob([barr], {type: "image/jpeg"});
		return blob;
	}

    /*$("#cautionModal").css({
        display:"block",
        opacity:1
    });

    $("#fileInput").change(function(e){
        sendImage(this);
    });

    $("#re_fileInput").change(function(e){
        sendImage(this);
    });

    function sendImage(that){
        $("#snap_caution").hide();

        $("#snap_error").hide();
        $("#snap_ok").hide();
        $("#snap_result").show();

        $("#sub_title").text("読み取り内容の確認");

        console.log(that.files[0]);
		var file = that.files[0];

		var reader = new FileReader();
        reader.onload = function(){
			$("#camImage").unbind('load');
			$("#camImage").bind('load', function(){
				var data = new FormData();
            	var blob = image2blob($("#camImage").get(),true);
				data.append("image", blob);

				$("this").attr("src",blob);
				$data ={
					url: '/assets/ajax/post_receipt_image.php',
					type: 'POST',
					data:data,
					contentType: false,
					processData: false
				};
				callFrontAjax($data, function(data){
					console.log(data);
					var res = JSON.parse(data).result;

					if(res && res.items && res.items.length > 0){
                        console.log(res.items[0].priceInfo);
					    console.log(res.items);

						elem = "<p>日付:"+res.paymentInfo.date+" "+res.paymentInfo.time+"</p>\
									<p>店舗:"+res.storeInfo.name+" "+res.storeInfo.branch+"</p>\
									<p>住所:"+res.storeInfo.address+"</p>\
									<p>Tel:"+res.storeInfo.tel+"</p>";
						if(res.items.length > 0){
							for(var n=0;n<res.items.length;n++){
								elem += "<p>商品:"+res.items[n].name+" "+res.items[n].count+"*@"+res.items[n].priceInfo.unitPrice+"</p>\
									<p>金額:"+res.items[n].priceInfo.price+"</p>";
							}
						}
                        var val = (res.items[0].count-0)/100.0;
                        $("#res_input").val(""+val);
                        $("#snap_ok").show();
					}
                    else{
                        $("#snap_error").show();
                    }
				});
			});

            $("#camImage").attr("src",reader.result);
			
        }
        reader.readAsDataURL(file);
    }

	function image2blob(img,isResize){

		var image = new Image();
		image.src = $(img).attr("src");


		var maxWidth = 3024.0/4;
		var maxHeight = 4032.0/4;

		var width,height;
				
		if(isResize && (image.width > maxWidth || image.height > maxHeight)){
			var ratio = image.width/image.height;
			if(image.width-maxWidth > image.height-maxHeight){
				width = maxWidth;
				height = maxWidth/ratio;
			}
			else{
				width = maxHeight*ratio;
				height = maxHeight;
			}
		}
		else{
			width = image.width;
			height = image.height;
		}
			

		var canvas = document.getElementById("resize_canvas");
		canvas.width = width;
		canvas.height = height;
		var context = canvas.getContext("2d");
		context.clearRect(0,0,width,height);
		context.drawImage(image,0,0,image.width,image.height,0,0,width,height);

		var imgData = context.getImageData(0,0,image.width,image.height);

		var data = imgData.data;
			
		var rRate=0.34;
		var gRate=0.33;
		var bRate=0.33;
		
		for(var n=0,len=imgData.width*imgData.height;n<len;n++){
			var r=data[n*4];
    		var g=data[n*4+1];
        	var b=data[n*4+2];        		
        		
        	data[n*4]=Math.round(rRate*r+gRate*g+bRate*b);
    		data[n*4+1]=Math.round(rRate*r+gRate*g+bRate*b);
    		data[n*4+2]=Math.round(rRate*r+gRate*g+bRate*b);
        }


		context.clearRect(0,0,width,height);
		context.putImageData(imgData,0,0);

		var base64 = canvas.toDataURL("image/jpeg", 0.5);
		var barr, bin, i, len;
		bin = atob(base64.split('base64,')[1]);
		len = bin.length;
		barr = new Uint8Array(len);
		i = 0;
		while (i < len) {
			barr[i] = bin.charCodeAt(i);
			i++;
		}
		blob = new Blob([barr], {type: "image/jpeg"});

		return blob;
	}*/


	// Ajax
    function callFrontAjax($data, fncSuccess, fncFail){
        //console.log($data);
        $.ajax($data)
            .done( function(data, textStatus, jqXHR){
                console.log(data);
                console.log(textStatus);
                console.log(jqXHR);
                console.log(jqXHR.responseText);
            
                if(fncSuccess != undefined){
                        fncSuccess(data);
                    }
                })
            // 失敗
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert('サーバとの通信に失敗しました');
                console.log(jqXHR);
                console.log(jqXHR.responseText);
                console.log(textStatus);
                console.log(errorThrown);
                if(fncFail != undefined){
                    fncFail(data);
                }
            })
            // Ajaxリクエストが成功・失敗どちらでも発動
                .always( function(data) {
        });
    }




}());













