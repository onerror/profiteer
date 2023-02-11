$(function ($) {
	let chart = new Chart("dashboardChart", {
		type: "line",
		options: {
			legend: { display: false },
			autoUpdateInput: true, autoApply: true
		}
	});
	
	let refreshChart = function (graphDates, ordersData, customersData) {
		chart.data = {
			labels: graphDates,
			datasets: [{
				data: ordersData,
				borderColor: "red",
				fill: false
			}, {
				data: customersData,
				borderColor: "green",
				fill: false
			},
			]
		};
		chart.update();
	}
	
	let refreshIndicators = function (ordersCount, customersCount, revenue) {
		$("#orders_tally").text("Orders: " + ordersCount);
		$("#customers_tally").text("Customers: " + customersCount);
		$("#revenue").text("Revenue: " + revenue);
	}
	
	
	let updateDashboardData = function (start, end) {
		$.ajax({
			type: 'GET',
			url: '/api/dashboard_data/' + start + '/' + end,
			dataType: "json",
			success: function (result, textStatus, jqXHR) {
				refreshIndicators(result["orders_count"], result["customers_count"], result["revenue"])
				refreshChart(result['graph_dates'], result['orders_tally_graph'], result['customers_tally_graph']);
			}
		});
	}
	
	
	let dateRangePickerEl = $('input[name="stat_dates_filter"]');
	let datesAr = dateRangePickerEl.val().split(' - ')
	
	updateDashboardData(new Date(datesAr[0]).valueOf(), new Date(datesAr[1]).valueOf());
	console.log(new Date(datesAr[0]).valueOf());
	console.log(new Date(datesAr[1]).valueOf());
	dateRangePickerEl.daterangepicker({
		opens: 'left'
	}, function (start, end, label) {
		updateDashboardData(start, end);
	});
	
	
});