

function selectOne(id, img) {
        $('#selectAllByOne').attr('src', '/images/card-nochoose.png');
        if($("input[name='cart["+id+"]']").val() == 0){
            $('#' + img).attr('src','/images/card-choose.png');$("input[name='cart["+id+"]']").val(1);
        }else{
            $('#' + img).attr('src','/images/card-nochoose.png');$("input[name='cart["+id+"]']").val(0);
        }
        sumTotal();
    }

    function sumTotal() {
        var price = 0;
		for (var i=0; i< {{$index}}; i++){	
			if($("input[name='cart["+i+"]']").val() == 1){		
				price += parseFloat($("input[name='cart["+i+"]']").next().val());
			}
		}
        $('#totals').html(price);
    }

    function selectAll() { 
		for (var i=0; i< {{$index}}; i++){
			$("input[name='cart["+i+"]']").val(1);	
		}
        $('label img').attr('src', '/images/card-choose.png');
        $('#selectAllByOne').attr('src', '/images/card-choose.png');
        sumTotal();
    }

        function increment(k) {
			$('#qualityshow'+k).val(parseInt($('#qualityshow'+k).val()) + 1);
            $('#quantity'+k).val(parseInt($('#quantity'+k).val()) + 1);
            countTotal(k);
        }
        function countTotal(k) {
            var q = $('#onePrice'+k).val();
            $('#showPrice'+k).html((parseFloat($('#qualityshow'+k).val()) * q).toFixed(2));
			$('#subTotal'+k).val((parseFloat($('#qualityshow'+k).val()) * q).toFixed(2));
			sumTotal();
        }

        function decrement(k) {
            var num = parseInt($('#qualityshow'+k).val());
            if (num > 1) {
				$('#qualityshow'+k).val(parseInt($('#qualityshow'+k).val()) - 1);
				$('#quantity'+k).val(parseInt($('#quantity'+k).val()) - 1);
				countTotal(k);
            }
        }
