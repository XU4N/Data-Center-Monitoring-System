<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\IntelligentPredict;
use Illuminate\Support\Collection;

class IntelligentPredictionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    protected $intelligent;
    protected $readings;
    protected $readingsGoingDown;
    protected $readingsGoingUp;
    protected $expectedDbReadings;

    public function setUp() {
    	parent::setUp();

    	$this->intelligent = new IntelligentPredict();
    	$this->readings = App\Reading::thisMonth()->get();
    	$this->randomReadings = collect([24, 25, 22]);
    	$this->randomGoingDownReadings = collect([22, 21, 20]);
    	$this->readingsGoingUp = collect([23, 25, 27]);
    	$this->readingsGoingDown = collect([22, 21, 20]);
    	$this->expectedDbReadings = collect([23.468, 22.2, 26.0]);

    }

    public function testDefaultTemperature() {
    	$normal_temperature = $this->intelligent->getDefaultTemperature();
    	$this->assertEquals(23.0, $normal_temperature);
    }

    // public function testForAverageReadingIsNotNull(){
    // 	$averageReadingForThisMonth = $this->intelligent->getAverageReadingForThisMonth();
    // 	$this->assertNotNull($averageReadingForThisMonth, "The average reading is null");
    // }

    public function testForRisingTemperature() {
    	$this->assertEquals(3, $this->intelligent->calculateTrend($this->readingsGoingUp));
    }

    public function testForDecreasingTemperature() {
    	$this->assertEquals(3, $this->intelligent->calculateTrend($this->readingsGoingDown));
    }

    public function testRandomTemperature() {
        echo "Testing random temperature readings\n";
	    $this->assertEquals(0, $this->intelligent->calculateTrend($this->randomReadings));	

    }

    public function testRandomTemperatureGoingDown() {
	    $this->assertEquals(3, $this->intelligent->calculateTrend($this->randomGoingDownReadings));	
    }

    public function testDeltaFunctionForReadingsGoingUp() {
    	echo "\nTemperature Going Up Exceeded\n";
    	$this->assertEquals(4 ,$this->intelligent->calculateDelta($this->readingsGoingUp));
    }

    public function testDeltaFunctionForReadingsGoingDown() {
    	$this->assertEquals(-2 ,$this->intelligent->calculateDelta($this->readingsGoingDown));
    }

    public function testFeedbackMessageForReadingsGoingDown() {
    	$this->intelligent->calculateTrend($this->readingsGoingDown);
    	$this->assertEquals("The temperature is decreasing steadily. Overcooling the data centre may cost you with high energy bills.", $this->intelligent->getFeedbackMessage($this->readingsGoingDown));
    }

    public function testFeedbackMessageForReadingsGoingUp() {
    	$this->intelligent->calculateTrend($this->readingsGoingUp);
    	$this->assertEquals("The temperature is rising steadily. Further increase in the temperature may affect the performance of the servers", $this->intelligent->getFeedbackMessage($this->readingsGoingUp));
    }

    // public function testIntelligentPredictDatabaseQueriedReadingsForZone1() {
    // 	$this->assertEquals($this->expectedDbReadings, IntelligentPredict::prepareReadings(1));
    // }

    public function testMonitoringPeriod() {
    	$this->assertEquals(3, IntelligentPredict::getMonitoringPeriod());
    }

    public function testMonitoringPeriodDescription() {
    	echo ("\n".$this->intelligent->getMonitoringPeriodInWords()."\n");
    }

    public function testMonitoringPeriodLegend() {
        echo "\n";
        echo "---------------------\n";
        echo "Testing legend output\n";
        echo "---------------------\n";

        $legend = $this->intelligent->getMonitoringPeriodLegend();

        for ($i=0; $i < count($legend); $i++) { 
            echo " - ".$legend[$i]."\n";
        }
    }
}
