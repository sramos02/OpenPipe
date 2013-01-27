#------------------------------------------------------------
# Script for recording performance timing of a given web page
# @author Sean Kenny <skenny214@gmail.com>
#------------------------------------------------------------
require 'pp'
require 'rubygems'
require 'csv'
require 'selenium-webdriver'

#get arguments
url = ARGV[0];
cycles = ARGV[1] || '5'
output_file = ARGV[2] || 'output.csv'
do_cache = ARGV[3] || 1;

if(do_cache.to_i == 1)
	browser = Selenium::WebDriver.for :firefox
	browser.get url
end

#open csv for writing output data
CSV.open(output_file, "w") do |csv|
	#for the amount of times the user wanted, get the page, get the performance timing, and output to csv
	cycles.to_i.times do |i|	

		if(do_cache.to_i == 0)
			browser.quit if browser != nil
			browser = Selenium::WebDriver.for :firefox
		end

		browser.get url
		browser_timing = browser.execute_script("return window.performance.timing"); 
		openpipe_timing = browser.execute_script("return typeof(op) !== 'undefined' ? op.performance.timing.segments : null");

		# calculate wait time in two ways - if not openpipe then just use reponse start
		if(openpipe_timing == nil)
			browser_timing['responseWaitTime'] = browser_timing['responseStart'] - browser_timing['requestStart']

		#if we have openpipe timing then the response wait time is for first piece of actual content (assumed to be pipelet of first priority)
		else		
			browser_timing['responseWaitTime'] = openpipe_timing[1] - browser_timing['requestStart']
		end

		browser_timing['totalLoadWaitTime'] = browser_timing['loadEventEnd'] - browser_timing['requestStart'] 
		
		sorted_timing_values = []
		browser_timing.keys.sort.each do |key| 
			sorted_timing_values << browser_timing[key]
		end
		
		csv << browser_timing.keys.sort if i==0
		csv << sorted_timing_values
	end
end

browser.quit