<?php function print_pl_edit_data($r){ ?>
<div id="tabs-1" class="tabcontent ui-tabs-panel ui-widget-content ui-corner-bottom">
            <table width="100%" border="0" cellpadding="5" id="invDetail" class="InfoForm">
                <tbody><tr class="even gradeC" style="font-weight:bold;">
                    <td>Max No of Study</td>
                    <td>Balance Study</td>
                    <td>RR%</td>
                    <td>Invited</td>
                    <td>Redirected</td>
                    <td>Completed</td>
                    <td>Disqualified</td>
                    <td>Quota Full</td>
                    <td>Earned Points</td>
                    <td>Balance Points</td>
                </tr>
                <tr class="odd gradeC">
                    <td><?php echo $r['max_no_study'];?></td>
                    <td><?php echo $r['balance_no_study'];?></td>
                    <td>??</td>
                    <td><?php echo $r['no_invited'];?></td>
                    <td><?php echo $r['no_redirected'];?></td>
                    <td><?php echo $r['no_completed'];?></td>
                    <td><?php echo $r['no_disqualified'];?></td>
                    <td><?php echo $r['no_qfull'];?></td>
                    <td><?php echo $r['earn_points'];?></td>
                    <td><?php echo $r['balance_points'];?></td>
                </tr>
            </tbody></table>
            <br>
            <h2>General Information</h2>
            <table width="100%" border="0" cellpadding="5" id="invDetail" class="InfoForm">
                                <tbody><tr class="odd gradeC ">
                    <td>Status</td>
                    <td>
                        Enable&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="profile.php?panelist_id=8421&amp;status=D" style="color:#447BF7;" onclick="return confirmSubmit('D');">Click here to Disable Panelist</a></td>
                </tr>

                                <tr class="odd gradeC ">
                    <td>Fraud</td>
                    <td>
                        No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="profile.php?panelist_id=8421&amp;fraud=1" style="color:#447BF7;" onclick="return confirmSubmitfraud('1');">Click to mark Panelist as Fraud</a></td>

                </tr>

                <tr class="even gradeC">
                    <td width="50%">Date of Birth</td>
                    <td>09-16-1982</td>
                </tr>
                <tr class="odd gradeC">
                    <td>Gender</td>
                    <td>Male</td>
                </tr>
                <tr class="even gradeC">
                    <td>Zip/Postal Code</td>
                    <td>19044</td>
                </tr>
                <tr class="even gradeC">
                    <td>Approximately how many employees work for your company (all locations)?</td>
                    <td>500 - 999 employees</td>
                </tr>
                <tr class="odd gradeC">
                    <td>Which best describes your company's primary industry?</td>
                    <td>Computer - Reseller / VAR</td>
                </tr>
                <tr class="even gradeC">
                    <td>Which best describes your title or position?</td>
                    <td>Technical Staff - IT Developer</td>
                </tr>
            </tbody></table>
            <br>
            <h2>Professional Information</h2>
            <table width="100%" border="0" cellpadding="5" id="invDetail" class="InfoForm">
                <tbody><tr class="odd gradeC"><td colspan="2">Which paid market research topics would you like to participate in?</td></tr><tr class="even gradeC"><td>Both (any paid market research opportunity)</td><td></td></tr><tr class="odd gradeC"><td width="50%">What is your current employment status?</td><td></td></tr><tr class="even gradeC"><td width="50%">Are you a Small Business Owner?</td><td></td></tr><tr class="odd gradeC"><td width="50%">Approximately how many computers are in your place of employment (All locations)?</td><td></td></tr><tr class="even gradeC"><td width="50%">Which of the following best describes your ethnicity?</td><td></td></tr><tr class="odd gradeC"><td width="50%">Are you of Latino or Hispanic descent?</td><td></td></tr><tr class="even gradeC"><td width="50%">What is your marital status?</td><td></td></tr><tr class="odd gradeC"><td width="50%">What is your highest level of completed education?</td><td></td></tr><tr class="even gradeC"><td width="50%">What is your total annual household income? </td><td></td></tr><tr class="odd gradeC"><td width="50%">Do you own or lease an automobile? </td><td></td></tr><tr class="even gradeC"><td width="50%">Have you flown for work or pleasure within the last year? </td><td></td></tr><tr class="odd gradeC"><td width="50%">Which type(s) of paid market research opportunities in addition to online surveys would you like to receive?</td><td></td></tr><tr class="even gradeC"><td width="50%">Which paid market research topics would you like to participate in?</td><td></td></tr><tr class="odd gradeC"><td width="50%">Do you make or influence purchasing decisions for your company? </td><td></td></tr><tr class="even gradeC"><td width="50%">Which of the following additional languages (if any) do you feel comfortable completing surveys in?</td><td></td></tr><tr class="odd gradeC"><td width="50%">do you like tests? </td><td></td></tr>            </tbody></table>
        </div>
<?php } ?>

<?php function print_pl_view_data($r){ ?>
<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
            <form name="frmadd" action="index.php?controller=panelist&amp;action=panelist&amp;subaction=editpanelistprofile" method="post" enctype="multipart/form-data">
                <input type="hidden" value="8421" id="panelist_id" name="panelist_id">
                <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
                    <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">Registration Information</a></li>
                    <li class="ui-state-default ui-corner-top"><a href="#tabs-2">Profile Information</a></li>
                </ul>
                <div id="tabs-1" class="tabcontent ui-tabs-panel ui-widget-content ui-corner-bottom">
                    <table width="100%" border="0" cellpadding="5" id="invDetail" class="InfoForm">
                        <tbody><tr class="even gradeC">
                            <td width="400">Email Address</td>
                            <td>brennanhu.ffsb@gmail.com</td>
                        </tr>
                        <tr class="odd gradeC">
                            <td>First Name</td>
                            <td><input type="text" value="Bill" class="select" id="fname" name="fname"></td>
                        </tr>
                        <tr class="even gradeC">
                            <td>Last Name</td>
                            <td><input type="text" value="Parker" class="select" id="lname" name="lname"></td>
                        </tr>
                        <tr class="odd gradeC">
                            <td>Date of Birth</td>
                            <td><input type="text" name="6" id="6" class="select" value="09-16-1982"> <input type="hidden" name="alt" id="alt" class="select"></td>
                        </tr>
                        <tr class="even gradeC">
                            <td>Zip/Postal Code</td>
                            <td><input type="text" value="19044" class="select" id="2" name="2"></td>
                        </tr>
                        <tr class="odd gradeC">
                            <td>Country</td>
                            <td><select name="CountryID" class="select"><option value="" selected="">Select Country</option><option value="1">Afghanistan</option><option value="2">Albania</option><option value="3">Algeria</option><option value="4">American Samoa</option><option value="5">Andorra</option><option value="6">Angola</option><option value="7">Anguilla</option><option value="8">Antarctica</option><option value="9">Antigua and Barbuda</option><option value="10">Argentina</option><option value="11">Armenia</option><option value="12">Aruba</option><option value="13">Australia</option><option value="14">Austria</option><option value="15">Azerbaijan</option><option value="16">Bahamas</option><option value="17">Bahrain</option><option value="18">Bangladesh</option><option value="19" selected="">Barbados</option><option value="20">Belarus</option><option value="21">Belgium</option><option value="22">Belize</option><option value="23">Benin</option><option value="24">Bermuda</option><option value="25">Bhutan</option><option value="26">Bolivia</option><option value="27">Bosnia and Herzegowina</option><option value="28">Botswana</option><option value="29">Bouvet Island</option><option value="30">Brazil</option><option value="31">British Indian Ocean Territory</option><option value="32">Brunei Darussalam</option><option value="33">Bulgaria</option><option value="34">Burkina Faso</option><option value="35">Burundi</option><option value="36">Cambodia</option><option value="37">Cameroon</option><option value="38">Canada</option><option value="39">Cape Verde</option><option value="40">Cayman Islands</option><option value="41">Central African Republic</option><option value="42">Chad</option><option value="43">Chile</option><option value="44">China</option><option value="45">Christmas Island</option><option value="46">Cocos (Keeling) Islands</option><option value="47">Colombia</option><option value="48">Comoros</option><option value="49">Congo</option><option value="50">Cook Islands</option><option value="51">Costa Rica</option><option value="52">Cote DIvoire</option><option value="53">Croatia</option><option value="54">Cuba</option><option value="55">Cyprus</option><option value="56">Czech Republic</option><option value="57">Denmark</option><option value="58">Djibouti</option><option value="59">Dominica</option><option value="60">Dominican Republic</option><option value="61">East Timor</option><option value="62">Ecuador</option><option value="63">Egypt</option><option value="64">El Salvador</option><option value="65">Equatorial Guinea</option><option value="66">Eritrea</option><option value="67">Estonia</option><option value="68">Ethiopia</option><option value="69">Falkland Islands (Malvinas)</option><option value="70">Faroe Islands</option><option value="71">Fiji</option><option value="72">Finland</option><option value="73">France</option><option value="74">France, Metropolitan</option><option value="75">French Guiana</option><option value="76">French Polynesia</option><option value="77">French Southern Territories</option><option value="78">Gabon</option><option value="79">Gambia</option><option value="80">Georgia</option><option value="81">Germany</option><option value="82">Ghana</option><option value="83">Gibraltar</option><option value="84">Greece</option><option value="85">Greenland</option><option value="86">Grenada</option><option value="87">Guadeloupe</option><option value="88">Guam</option><option value="89">Guatemala</option><option value="90">Guinea</option><option value="91">Guinea-bissau</option><option value="92">Guyana</option><option value="93">Haiti</option><option value="94">Heard and Mc Donald Islands</option><option value="95">Honduras</option><option value="96">Hong Kong</option><option value="97">Hungary</option><option value="98">Iceland</option><option value="99">India</option><option value="100">Indonesia</option><option value="101">Iran (Islamic Republic of)</option><option value="102">Iraq</option><option value="103">Ireland</option><option value="104">Israel</option><option value="105">Italy</option><option value="106">Jamaica</option><option value="107">Japan</option><option value="108">Jordan</option><option value="109">Kazakhstan</option><option value="110">Kenya</option><option value="111">Kiribati</option><option value="112">Korea, Democratic People Republic</option><option value="113">Korea, Republic of</option><option value="114">Kuwait</option><option value="115">Kyrgyzstan</option><option value="116">Lao People Democratic Republic</option><option value="117">Latvia</option><option value="118">Lebanon</option><option value="119">Lesotho</option><option value="120">Liberia</option><option value="121">Libyan Arab Jamahiriya</option><option value="122">Liechtenstein</option><option value="123">Lithuania</option><option value="124">Luxembourg</option><option value="125">Macau</option><option value="126">Macedonia, The Former Yugoslav Republic of</option><option value="127">Madagascar</option><option value="128">Malawi</option><option value="129">Malaysia</option><option value="130">Maldives</option><option value="131">Mali</option><option value="132">Malta</option><option value="133">Marshall Islands</option><option value="134">Martinique</option><option value="135">Mauritania</option><option value="136">Mauritius</option><option value="137">Mayotte</option><option value="138">Mexico</option><option value="139">Micronesia, Federated States of</option><option value="140">Moldova, Republic of</option><option value="141">Monaco</option><option value="142">Mongolia</option><option value="143">Montserrat</option><option value="144">Morocco</option><option value="145">Mozambique</option><option value="146">Myanmar</option><option value="147">Namibia</option><option value="148">Nauru</option><option value="149">Nepal</option><option value="150">Netherlands</option><option value="151">Netherlands Antilles</option><option value="152">New Caledonia</option><option value="153">New Zealand</option><option value="154">Nicaragua</option><option value="155">Niger</option><option value="156">Nigeria</option><option value="157">Niue</option><option value="158">Norfolk Island</option><option value="159">Northern Mariana Islands</option><option value="160">Norway</option><option value="161">Oman</option><option value="162">Pakistan</option><option value="163">Palau</option><option value="164">Panama</option><option value="165">Papua New Guinea</option><option value="166">Paraguay</option><option value="167">Peru</option><option value="168">Philippines</option><option value="169">Pitcairn</option><option value="170">Poland</option><option value="171">Portugal</option><option value="172">Puerto Rico</option><option value="173">Qatar</option><option value="174">Reunion</option><option value="175">Romania</option><option value="176">Russian Federation</option><option value="177">Rwanda</option><option value="178">Saint Kitts and Nevis</option><option value="179">Saint Lucia</option><option value="180">Saint Vincent and the Grenadines</option><option value="181">Samoa</option><option value="182">San Marino</option><option value="183">Sao Tome and Principe</option><option value="184">Saudi Arabia</option><option value="185">Senegal</option><option value="186">Seychelles</option><option value="187">Sierra Leone</option><option value="188">Singapore</option><option value="189">Slovakia (Slovak Republic)</option><option value="190">Slovenia</option><option value="191">Solomon Islands</option><option value="192">Somalia</option><option value="193">South Africa</option><option value="194">South Georgia and the South Sandwich Islands</option><option value="195">Spain</option><option value="196">Sri Lanka</option><option value="197">St. Helena</option><option value="198">St. Pierre and Miquelon</option><option value="199">Sudan</option><option value="200">Suriname</option><option value="201">Svalbard and Jan Mayen Islands</option><option value="202">Swaziland</option><option value="203">Sweden</option><option value="204">Switzerland</option><option value="205">Syrian Arab Republic</option><option value="206">Taiwan</option><option value="207">Tajikistan</option><option value="208">Tanzania, United Republic of</option><option value="209">Thailand</option><option value="210">Togo</option><option value="211">Tokelau</option><option value="212">Tonga</option><option value="213">Trinidad and Tobago</option><option value="214">Tunisia</option><option value="215">Turkey</option><option value="216">Turkmenistan</option><option value="217">Turks and Caicos Islands</option><option value="218">Tuvalu</option><option value="219">Uganda</option><option value="220">Ukraine</option><option value="221">United Arab Emirates</option><option value="222">United Kingdom</option><option value="223">United States</option><option value="224">United States Minor Outlying Islands</option><option value="225">Uruguay</option><option value="226">Uzbekistan</option><option value="227">Vanuatu</option><option value="228">Vatican City State (Holy See)</option><option value="229">Venezuela</option><option value="230">Viet Nam</option><option value="231">Virgin Islands (British)</option><option value="232">Virgin Islands (U.S.)</option><option value="233">Wallis and Futuna Islands</option><option value="234">Western Sahara</option><option value="235">Yemen</option><option value="236">Yugoslavia</option><option value="237">Zaire</option><option value="238">Zambia</option><option value="239">Zimbabwe</option><option value="240">test</option><option value="241">International</option><option value="242">testing</option></select></td>
                        </tr>
                        <tr class="even gradeC">
                            <td>Approximately how many employees work for your company (all locations)?</td>
                            <td><select name="3" class="select">
                                    <option value="">Please Provide Answer</option>
                                    <option value="262">1 employee</option><option value="3">2 - 5 employees</option><option value="4">6 - 9 employees</option><option value="298">10 - 19 employees</option><option value="299">20 - 49 employees</option><option value="6">50 - 99 employees</option><option value="7">100 - 249 employees</option><option value="8">250 - 499 employees</option><option value="9" selected="">500 - 999 employees</option><option value="10">1,000 - 2,499 employees</option><option value="11">2,500 - 4,999 employees</option><option value="12">5,000 - 9,999 employees</option><option value="179">10,000+ employees</option><option value="263">Unknown / Unemployed</option>                                </select>
                            </td>
                        </tr>
                        <tr class="odd gradeC">
                            <td>Which best describes your company's primary industry?</td>
                            <td><select name="4" class="select">
                                    <option value="">Please Provide Answer</option>
                                    <option value="328">testing 123</option><option value="13">Accounting</option><option value="14">Advertising</option><option value="15">Aerospace / Defense</option><option value="16">Agriculture / Forestry</option><option value="17">Airlines</option><option value="18">Architecture</option><option value="19">Art</option><option value="20">Automotive</option><option value="21">Banking</option><option value="22">Beauty / Cosmetics</option><option value="23">Business / Professional Services</option><option value="24">Casino</option><option value="25">Catering</option><option value="26">Chemical</option><option value="27">Caregiver (Adult, Child, Senior)</option><option value="28">Computer - Hardware</option><option value="29">Computer - Software</option><option value="30" selected="">Computer - Reseller / VAR</option><option value="31">Construction</option><option value="32">Consulting</option><option value="33">Consumer Packaged Goods</option><option value="34">Dental</option><option value="35">Education</option><option value="36">Electronics</option><option value="37">Energy</option><option value="38">Engineering</option><option value="39">Emergency Services (EMT, Fire, Police)</option><option value="40">Entertainment / Media (TV/Film)</option><option value="41">Fashion / Apparel</option><option value="42">Financial Services (Non-Banking)</option><option value="43">Food / Beverage</option><option value="44">Government - Local</option><option value="45">Government - State</option><option value="46">Government - Federal</option><option value="47">Healthcare</option><option value="48">Hotel / Hospitality</option><option value="49">Human Resources</option><option value="50">Import / Export</option><option value="51">Information Technology</option><option value="52">Insurance</option><option value="53">Legal</option><option value="54">Manufacturing</option><option value="55">Marketing </option><option value="56">Market Research</option><option value="57">Medical</option><option value="58">Military</option><option value="59">Mortgage</option><option value="273">Music</option><option value="274">Non-Profit</option><option value="275">Pet / Animal Care</option><option value="276">Pharmaceutical</option><option value="277">Photography</option><option value="278">Politics</option><option value="279">Public Relations</option><option value="280">Publishing / Printing</option><option value="281">Real Estate</option><option value="282">Religion</option><option value="283">Restaurant</option><option value="284">Retail</option><option value="285">Shipping / Distribution</option><option value="286">Skilled Trade</option><option value="287">Social Services</option><option value="288">Technology</option><option value="289">Telecommunications / Communications</option><option value="290">Transportation</option><option value="291">Travel</option><option value="292">Utilities</option><option value="293">Wholesale</option><option value="294">Unemployed</option><option value="302">Other</option>                                </select></td>
                        </tr>
                        <tr class="even gradeC">
                            <td>Which best describes your title or position?</td>
                            <td><select name="5" class="select">
                                    <option value="">Please Provide Answer</option>
                                    <option value="60">Business Owner</option><option value="61">Partner / Principle</option><option value="62">President</option><option value="63">C-Level Executive (CEO, CIO, etc.)</option><option value="64">Senior Vice President</option><option value="65">Vice President    </option><option value="66">Controller / Comptroller</option><option value="67">Director</option><option value="68">Senior Manager</option><option value="69">Manager</option><option value="70">Supervisor</option><option value="71">Technical Staff - IT Administrator</option><option value="72" selected="">Technical Staff - IT Developer</option><option value="73">Technical Staff - IT Manager / Director</option><option value="74">Technical Staff - Help Desk / Other IT Support</option><option value="75">Craftsman / Foreman</option><option value="76">Faculty Staff</option><option value="77">Sales</option><option value="78">Administrative / Clerical</option><option value="264">Non-Management Staff</option><option value="295">Unemployed</option><option value="272">Other</option>                                </select></td>
                        </tr>
                    </tbody></table>	
                    <div class="clear">&nbsp;</div>
                </div>
                <div id="tabs-2" class="tabcontent ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
                    <table width="100%" border="0" cellpadding="5" id="invDetail" class="InfoForm">
                        <tbody><tr class="even gradeC">
				<td><strong>How often would you like to receive paid market research opportunities?</strong><br>
				<input type="radio" name="max_no_study" value="4"> Occassionally (monthly opportunities)<br>
				<input type="radio" name="max_no_study" value="12"> Frequently (weekly opportunities)<br>
				<input type="radio" name="max_no_study" value="30"> Regularly (daily opportunities)<br>
				<input type="radio" name="max_no_study" value="0" checked=""> Please send me all opportunities.
				</td></tr><tr class="odd gradeC">
				<td><strong>Which paid market research topics would you like to participate in?</strong><br>
				<input type="radio" name="topics" value="1" onclick="showhide('businessdiv');">Business topics (topics related ONLY to my profession or decision making authority)<br>
				<input type="radio" name="topics" value="2" onclick="showhide('consumerdiv');">Consumer topics (topics related to every day products or services)<br>
				<input type="radio" name="topics" value="3" onclick="showhide('bothdiv');" checked="">Both (any paid market research opportunity)<br>
				</td></tr>				
				<tr class="even gradeC">
							<td>
							<div id="businessdiv" style="display:none">
								<input type="checkbox" name="7[]" id="7" value="167">Accounting&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7" value="168">Advertising&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7" value="169">Agriculture/Forestry&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7" value="170">Architecture&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7" value="268">NEED MORE!!&nbsp;&nbsp;
							</div>
							<div id="consumerdiv" style="display:none">
								<input type="checkbox" name="8[]" id="8" value="171">Illness&nbsp;&nbsp;<input type="checkbox" name="8[]" id="8" value="172">Geo Specific&nbsp;&nbsp;<input type="checkbox" name="8[]" id="8" value="173">Insurance/Brockers&nbsp;&nbsp;
							</div>
							<div id="bothdiv" style="display:block">
								<input type="checkbox" name="7[]" id="7" value="167">Accounting&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7" value="168">Advertising&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7" value="169">Agriculture/Forestry&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7" value="170">Architecture&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7" value="268">NEED MORE!!&nbsp;&nbsp;<input type="checkbox" name="8[]" id="8" value="171">Illness&nbsp;&nbsp;<input type="checkbox" name="8[]" id="8" value="172">Geo Specific&nbsp;&nbsp;<input type="checkbox" name="8[]" id="8" value="173">Insurance/Brockers&nbsp;&nbsp;
							</div></td>
					</tr>
				<tr class="odd gradeC"><td><strong>What is your current employment status?</strong> <select name="9" id="9"><option value="180">Student</option><option value="181">Active Military</option><option value="182">Self-employed / Business Owner</option><option value="183">Part Time employed</option><option value="184">Full Time employed</option><option value="185">Homemaker</option><option value="186">Retired</option><option value="187">Unemployed</option></select></td></tr>
				<tr class="even gradeC"><td><strong>Are you a Small Business Owner?</strong> <input type="radio" name="11" id="11" value="202">Yes&nbsp;&nbsp;<input type="radio" name="11" id="11" value="203">No&nbsp;&nbsp;</td></tr>
				<tr class="odd gradeC"><td><strong>Approximately how many computers are in your place of employment (All locations)?</strong> <select name="12" id="12"><option value="204">1</option><option value="205">2 - 4   </option><option value="206">5 - 9</option><option value="207">10 - 24</option><option value="208">25 - 49  </option><option value="209">50 - 99        </option><option value="210">100 - 249</option><option value="211">250 - 499</option><option value="212">500 - 999</option><option value="213">1,000+</option><option value="214">Unknown / Prefer not to say</option></select></td></tr>
				<tr class="even gradeC"><td><strong>Which of the following best describes your ethnicity?</strong> <select name="13" id="13"><option value="215">American Indian / Native American</option><option value="216">African American / Black</option><option value="217">Caucasian / White</option><option value="218">East Asian</option><option value="219">Middle Eastern</option><option value="220">Pacific Islander</option><option value="221">South Asian</option><option value="222">Multi-Racial</option><option value="223">Other</option><option value="303">Prefer not to say</option></select></td></tr>
				<tr class="odd gradeC"><td><strong>Are you of Latino or Hispanic descent?</strong> <input type="radio" name="14" id="14" value="224">Yes&nbsp;&nbsp;<input type="radio" name="14" id="14" value="225">No&nbsp;&nbsp;</td></tr>
				<tr class="even gradeC"><td><strong>What is your marital status?</strong> <select name="15" id="15"><option value="226">Single / Never Married</option><option value="227">Domestic Partnership</option><option value="228">Married</option><option value="229">Separated</option><option value="230">Divorced</option><option value="231">Widowed</option><option value="329">THIS IS A TEST</option><option value="330">HELLO SANTOSH</option></select></td></tr>
				<tr class="odd gradeC"><td><strong>What is your highest level of completed education?</strong> <select name="16" id="16"><option value="232">Less than High School Graduate </option><option value="233">High School Graduate</option><option value="234">Some College</option><option value="235">Trade School</option><option value="236">Associate\'s Degree</option><option value="237">Bachelor\'s Degree</option><option value="238">Master\'s Degree</option><option value="239">Doctorate, Law or Professional Degree</option></select></td></tr>
				<tr class="even gradeC"><td><strong>What is your total annual household income? </strong> <select name="17" id="17"><option value="240">$0 - $19,999</option><option value="241">$20,000 - $34,999</option><option value="242">$35,000 - $49,999</option><option value="243">$50,000 - $74,999</option><option value="244">$75,000 - $99,999</option><option value="245">$100,000 - $149,999</option><option value="246">$150,000 - $199,999</option><option value="247">$200,000 - $249,999</option><option value="248">$250,000+</option><option value="249">Prefer not to say</option><option value="324">100 Rs.</option><option value="325">200 Rs.</option><option value="326">300RS</option></select></td></tr>
				<tr class="odd gradeC"><td><strong>Do you own or lease an automobile? </strong> <input type="radio" name="18" id="18" value="254">Yes&nbsp;&nbsp;<input type="radio" name="18" id="18" value="255">No&nbsp;&nbsp;</td></tr>
				<tr class="even gradeC"><td><strong>Have you flown for work or pleasure within the last year? </strong> <input type="radio" name="19" id="19" value="256">Domestically&nbsp;&nbsp;<input type="radio" name="19" id="19" value="257">Internationally&nbsp;&nbsp;<input type="radio" name="19" id="19" value="260">Both Domestically and Internationally&nbsp;&nbsp;<input type="radio" name="19" id="19" value="261">I have not flown within the last year&nbsp;&nbsp;</td></tr>
				<tr class="odd gradeC"><td><strong>Which type(s) of paid market research opportunities in addition to online surveys would you like to receive?</strong> <input type="radio" name="23" id="23" value="269">Online focus groups/communities (typically requires multi-day online participation)&nbsp;&nbsp;<input type="radio" name="23" id="23" value="270">In-person focus groups and interviews (requires your participation at a local physical location)&nbsp;&nbsp;<input type="radio" name="23" id="23" value="271">Telephone interviews (requires your participation via telephone)&nbsp;&nbsp;<input type="radio" name="23" id="23" value="300">Text (SMS) surveys (requires your participation via mobile telelphone SMS/text)&nbsp;&nbsp;<input type="radio" name="23" id="23" value="301">In-home usage tests (requires your evaluation of a free product sent to your place of residence)&nbsp;&nbsp;</td></tr>
				<tr class="even gradeC"><td><strong>Which paid market research topics would you like to participate in?</strong> <input type="radio" name="22" id="22" value="265">Business topics (topics related ONLY to my profession or decision making authority)&nbsp;&nbsp;<input type="radio" name="22" id="22" value="266">Consumer topics (topics related to every day products or services)&nbsp;&nbsp;<input type="radio" name="22" id="22" value="267">Both (any paid market research opportunity)&nbsp;&nbsp;</td></tr>
				<tr class="odd gradeC"><td><strong>Do you make or influence purchasing decisions for your company? </strong> <input type="radio" name="24" id="24" value="296">Yes&nbsp;&nbsp;<input type="radio" name="24" id="24" value="297">No&nbsp;&nbsp;</td></tr>
				<tr class="even gradeC"><td><strong>Which of the following additional languages (if any) do you feel comfortable completing surveys in?</strong> <input type="checkbox" name="26[]" id="26" value="309">ENGLISH&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="310">Spanish&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="311">Chinese&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="312">Hindi&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="313">Russian&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="314">Bengali &nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="315">Portugese&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="316">German&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="317">French&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="318">Japanese&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="319">Urdu&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="320">Gujarati&nbsp;&nbsp;<input type="checkbox" name="26[]" id="26" value="321">Germany&nbsp;&nbsp;</td></tr>
				<tr class="odd gradeC"><td><strong>do you like tests? </strong> </td></tr>                    </tbody></table>
                </div><br>
                <!-- <input type="submit" class="text_btn" name="save" value="Update Profile"> &nbsp; <a href="index.php?controller=panelist&amp;action=panelist&amp;subaction=dashboard" class="link_btn" style="color:#fff;"> Cancel </a> -->

        </form></div>
<?php } ?>

<div class='header ui-widget-header'><?php $clang->eT("Manage Panel Lists"); ?></div><br />
<script>
    $(document).ready(function() {
        $('#listPanellist').dataTable({"sPaginationType": "full_numbers"});
    });
</script>

<table id="listPanellist" style="width:100%">
    <thead>
        <tr>
            <th width="25px"><?php $clang->eT("Edit"); ?></th>
            <th width="25px"><?php $clang->eT("View"); ?></th>
            <th width="auto"><?php $clang->eT("Panel list ID"); ?></th>
            <th width="auto"><?php $clang->eT("Email ID"); ?></th>
            <th width="auto"><?php $clang->eT("First Name"); ?></th>
            <th width="auto"><?php $clang->eT("Last Name"); ?></th>
            <th width="auto"><?php $clang->eT("Remote IP"); ?></th>
            <th width="auto"><?php $clang->eT("Status"); ?></th>
            <th width="auto"><?php $clang->eT("Is Fraud ?"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $dr = PL::model()->findAll();
        for ($i = 0; $i < count($dr); $i++) {
            $row = $dr[$i];
            ?>
            <tr>
                <td style="padding:3px;">
                    <?php
                    $this->widget("application.extensions.Brain.BrainPopupContentWidget", array(
                        "popup_box_id" => "box_edit_" . $row['panel_list_id'],
                        "popup_link_id" => "link_edit_" . $row['panel_list_id'],
                        "container_id" => "",
                        "popup_on_load" => "false",
                        "popup_title" => "Edit details of ".$row['first_name'].' '.$row['last_name'],
                        "uid" => $row['panel_list_id'],
                        "height" => "500px;",
                        "width" => "950px;",
                    ));
                    ?>
                    <a id="link_edit_<?php echo $row['panel_list_id']?>"><img src='<?php echo $imageurl; ?>edit_16.png' width="24px;" alt='<?php $clang->eT("Edit Panel List Profile Details"); ?>'/></a>
                </td>
                <td  style="padding:3px; width:25px">
                    <?php
                    $this->widget("application.extensions.Brain.BrainPopupContentWidget", array(
                        "popup_box_id" => "box_view_" . $row['panel_list_id'],
                        "popup_link_id" => "link_view_" . $row['panel_list_id'],
                        "container_id" => "",
                        "popup_on_load" => "false",
                        "popup_title" => "Viewing details of ".$row['first_name'].' '.$row['last_name'],
                        "uid" => $row['panel_list_id'],
                        "height" => "500px;",
                        "width" => "950px;",
                    ));
                    ?>
                    <a id="link_view_<?php echo $row['panel_list_id']?>"><img src='<?php echo $imageurl; ?>icon-view.png' width="24px;" alt='<?php $clang->eT("View Panel List Profile Details"); ?>'/></a>
                </td>
                <td><?php echo $row['panel_list_id']; ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['remote_ip']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars($row['is_fraud']); ?></td>
            </tr>
        <?php
        $row++;
    }
    ?>
</tbody>
</table>
<!-- generating div boxes for view and edit pop ups. Not possible in table's tr td. -->
<?php
$i=0;
for ($i = 0; $i < count($dr); $i++) {
    $row = $dr[$i];
    ?>
    <div id="box_edit_<?php echo $row['panel_list_id']?>">
        <?php print_pl_edit_data($row); ?>
    </div>
    <div id="box_view_<?php echo $row['panel_list_id']?>">
        <?php print_pl_view_data($row); ?>        
    </div>
    <?php
    $row++;
}
?>
