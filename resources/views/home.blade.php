@extends('layouts.master')
@section('style')
    <link href="{{asset('master/js/plugins/daterangepicker/daterangepicker.min.css')}}" rel="stylesheet">    
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Dashboard</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{__('page.expense')}} & {{__('page.income')}}</h3>
            </div>
            <div class="block-content block-content-full">
                <div id="transaction_chart" style="height: 400px;"></div>                
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{asset('master/js/plugins/echarts/echarts-en.js')}}"></script>
<script src="{{asset('master/js/plugins/daterangepicker/jquery.daterangepicker.min.js')}}"></script>
<script>
    var legend_array = {!! json_encode([__('page.income'), __('page.expense')]) !!};
    var income = "{{__('page.income')}}";
    var expense = "{{__('page.expense')}}";
        
    // console.log(legend_array);
    var Chart_overview = function() {

        var dashboard_chart = function() {
            if (typeof echarts == 'undefined') {
                console.warn('Warning - echarts.min.js is not loaded.');
                return;
            }

            // Define elements
            var area_basic_element = document.getElementById('transaction_chart');

            if (area_basic_element) {

                var area_basic = echarts.init(area_basic_element);

                area_basic.setOption({

                    color: ['#2ec7c9','#5ab1ef','#ff0000','#d87a80','#b6a2de'],

                    textStyle: {
                        fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                        fontSize: 13
                    },

                    animationDuration: 750,

                    grid: {
                        left: 0,
                        right: 40,
                        top: 35,
                        bottom: 0,
                        containLabel: true
                    },

                    
                    legend: {
                        data: [income, expense],
                        itemHeight: 8,
                        itemGap: 20
                    },

                    tooltip: {
                        trigger: 'axis',
                        backgroundColor: 'rgba(0,0,0,0.75)',
                        padding: [10, 15],
                        textStyle: {
                            fontSize: 13,
                            fontFamily: 'Roboto, sans-serif'
                        }
                    },

                    xAxis: [{
                        type: 'category',
                        boundaryGap: false,
                        data: {!! json_encode($key_array) !!},
                        axisLabel: {
                            color: '#333'
                        },
                        axisLine: {
                            lineStyle: {
                                color: '#999'
                            }
                        },
                        splitLine: {
                            show: true,
                            lineStyle: {
                                color: '#eee',
                                type: 'dashed'
                            }
                        }
                    }],

                    yAxis: [{
                        type: 'value',
                        axisLabel: {
                            color: '#333'
                        },
                        axisLine: {
                            lineStyle: {
                                color: '#999'
                            }
                        },
                        splitLine: {
                            lineStyle: {
                                color: '#eee'
                            }
                        },
                        splitArea: {
                            show: true,
                            areaStyle: {
                                color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                            }
                        }
                    }],

                    series: [
                        {
                            name: income,
                            type: 'line',
                            data: {!! json_encode($income_array) !!},
                            areaStyle: {
                                normal: {
                                    opacity: 0.25
                                }
                            },
                            smooth: true,
                            symbolSize: 7,
                            itemStyle: {
                                normal: {
                                    borderWidth: 2
                                }
                            }
                        },
                        {
                            name: expense,
                            type: 'line',
                            smooth: true,
                            symbolSize: 7,
                            itemStyle: {
                                normal: {
                                    borderWidth: 2
                                }
                            },
                            areaStyle: {
                                normal: {
                                    opacity: 0.25
                                }
                            },
                            data: {!! json_encode($expense_array) !!}
                        }
                    ]
                });
            }

            // Resize function
            var triggerChartResize = function() {
                area_basic_element && area_basic.resize();
            };

            // On sidebar width change
            $(document).on('click', '.sidebar-control', function() {
                setTimeout(function () {
                    triggerChartResize();
                }, 0);
            });

            // On window resize
            var resizeCharts;
            window.onresize = function () {
                clearTimeout(resizeCharts);
                resizeCharts = setTimeout(function () {
                    triggerChartResize();
                }, 200);
            };
        };

        return {
            init: function() {
                dashboard_chart();
            }
        }
    }();

    document.addEventListener('DOMContentLoaded', function() {
        Chart_overview.init();
    });

</script>
<script>
    $(document).ready(function () {
        $("#period").dateRangePicker();
    });
</script>
@endsection