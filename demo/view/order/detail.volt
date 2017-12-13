{% extends "templates/common.volt" %}
{% block title %}订单详情{% endblock %}
	{% block bodyContent %}

	<div class="ddqkdiv">
    	{{data['status_string']}}
    </div>
    <div class="shdz">
        <div>
            <strong><b>{{data['realname']}}</b>{{data['phone']}}</strong>
            <p>{{data['address']}}</p>
        </div>
    </div>
    <ul class="ddlist yh_dd">
    	{% for key,prd in data["orderproduct"] %}
    	<li>
			<a href="/product/detail?id={{prd['product']['id']}}">
				<div class="l dd_01">
					<img src="{{prd['product']['imgs']}}" alt="">
				</div>
				<div class="r dd_02">
					<strong class="sptit">{{prd['product']['name']}}</strong>
					<div class="djjs">
						<p>{{prd['merchant']['name']}}</p>
                        {% if prd['product_type'] is 1 %}
                        <p>服务日期：{{ prd['dtime'] }}</p>
                        <p>服务方式：{{ prd['store_type_string'] }}</p>
                        <p>服务点：{{ prd['store']['name'] }}</p>
                        {% endif %}
					</div>
					<div class="jggs">
						<strong><b>&yen;</b>{{prd['price']}}</strong>
						<i>x{{prd['num']}}</i>
					</div>
				</div>
				<div class="clr"></div>
			</a>
        </li>
        {% endfor %}
        
    </ul>
    <!--<p class="hysj"><span>还有三件</span></p>-->
    <a href="https://c81.kf5.com/kchat/27950?from=%E5%9C%A8%E7%BA%BF%E6%94%AF%E6%8C%81" class="lxkf">联系客服</a>
    <ul class="zffslist">
    	<li>
            <strong>支付方式</strong>
            <span>在线支付</span>
        </li>
        <li>
            <strong>配送方式</strong>
            <span>服务中心上门</span>
        </li>
        <li>
        	<strong>商品金额</strong>
            <b><i>&yen;</i>{{data['total_price']}}</b>
        </li>
        <li>
        	<strong>商品优惠</strong>
            <b><i>&yen;</i>0</b>
        </li>
    </ul>
    <div class="sfk">
    	<p><strong>实付款：</strong><b><i>&yen;</i>{{data['pay_amount']}}</b></p>
        <span>下单时间：{{data['ctime_string']}}</span>
    </div>
    {% if(data['status'] >= 4) and (data['commentExist'] is false) %}
    <div class="tjdddiv">
    	<a href="/user/orderComment?orderno={{data['orderno']}}" class="tjddbtn">晒单评价</a>	
    </div>
    {% endif %}
<script>
	$(function(){
		$('.hysj').click(function(){
			$('.yh_dd').css('height','auto');
			$(this).hide();
		});	
	});
</script>
{% endblock %}