using Microsoft.EntityFrameworkCore;
using WebSupplementalAPI;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
// Learn more about configuring Swagger/OpenAPI at https://aka.ms/aspnetcore/swashbuckle
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();
//Enable Cors
builder.Services.AddCors(p => p.AddPolicy("corsapp", builder =>
{
    builder.WithOrigins("*").AllowAnyMethod().AllowAnyHeader();
}));

builder.Services.AddDbContext<DataContext>(options =>
    options.UseSqlServer(builder.Configuration.GetConnectionString("DefaultConnection")));

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}
//Set Cors
app.UseCors("corsapp");
app.UseHttpsRedirection();

//Get all Advance Opinions
app.MapGet("/api/AdvanceOpinions", async (DataContext context) => await context.advanceopinions.OrderByDescending(s => s.date.Year).ThenByDescending(s => s.advanceNumber).ToListAsync());

//Get all admin orders
app.MapGet("/api/AdminOrders", async (DataContext context) => await context.adminorders.OrderByDescending(s => s.date).ToListAsync());

//Get all COA Unpublished Orders
app.MapGet("/api/COAUnpublishedOrders", async (DataContext context) => await context.coaunpublishedorders.OrderByDescending(s => s.date).ToListAsync());

//Get all Unpublished Orders
app.MapGet("/api/UnpublishedOrders", async (DataContext context) => await context.unpublishedorders.OrderByDescending(s => s.date).ToListAsync());

//Get all COA Oral Arguments
app.MapGet("/api/COAOralArguments", async (DataContext context) => await context.coaoralarguements.OrderByDescending(s => s.ArgumentDate).ToListAsync());
//Get all COA Oral Arguments
//app.MapGet("/api/OralArguments", async (DataContext context) => await context.oralArgCalendar.FromSql($"Select * ") OrderByDescending(s => s.ArgumentDate).ToListAsync());

//Get all Statistics
app.MapGet("/api/Statistics", async (DataContext context) => await context.statistics.OrderByDescending(s => s.year).ToListAsync());

//Get statistics by two courts and year
app.MapGet("/api/Statistics/{courtID1}/{courtID2}/{year}", async (int courtID1, int courtID2, int year, DataContext context) =>
{
    var courtRecordsbyID = await context.statistics.Where(s => s.courtID.Equals(courtID1)).ToArrayAsync();
    var courtRecordsbyYear = courtRecordsbyID.Where(s => s.year.Equals(year));

    if (courtID2 != 0)
    {
        var tempCourtRecordsbyID = await context.statistics.Where(s => s.courtID.Equals(courtID2)).ToArrayAsync();
        var tempCourtRecordsbyYear = tempCourtRecordsbyID.Where(s => s.year.Equals(year));
        courtRecordsbyYear = courtRecordsbyYear.Concat(tempCourtRecordsbyYear);
    }
    return courtRecordsbyYear.ToList();
});

//Get All Courts
app.MapGet("/api/Courts", async (DataContext context) => await context.courts.OrderBy(s => s.typeId).ThenBy(s => s.courtname).ToListAsync());

//Get oldest and newest years of statistics. 
app.MapGet("/api/Statistics/YearSelection", async (DataContext context) =>
{
    var minYear = await context.statistics.OrderBy(s => s.year).Take(1).ToArrayAsync();
    var maxYear = await context.statistics.OrderByDescending(s => s.year).Take(1).ToArrayAsync();
    var yearList = minYear.Concat(maxYear);

    return yearList.ToList();
});

//Get statstics of all courts in one record
app.MapGet("/api/Statistics/AllCourts/{year}", async (int year, DataContext context) =>
{
    var courtRecordsbyID = await context.statistics.Where(s => s.year.Equals(year)).OrderBy(s => s.courtID).ToArrayAsync();

    Statistics allCourts = new Statistics();
    allCourts.juvFilings = 0;
    allCourts.famFilings = 0;
    allCourts.juvDispo = 0;
    allCourts.famDispo = 0;

    for (int i = 0; i < courtRecordsbyID.Length; i++)
    {
        allCourts.crimFilings += courtRecordsbyID[i].crimFilings;
        allCourts.civilFilings += courtRecordsbyID[i].civilFilings;
        allCourts.juvFilings += courtRecordsbyID[i].juvFilings;
        allCourts.famFilings += courtRecordsbyID[i].famFilings;
        allCourts.trafficFilings += courtRecordsbyID[i].trafficFilings;
        allCourts.crimDispo += courtRecordsbyID[i].crimDispo;
        allCourts.civilDispo += courtRecordsbyID[i].civilDispo;
        allCourts.juvDispo += courtRecordsbyID[i].juvDispo;
        allCourts.famDispo += courtRecordsbyID[i].famDispo;
        allCourts.trafficDispo += courtRecordsbyID[i].trafficDispo;
    }

    allCourts.year = year;

    return allCourts;
});

//Get statstics of specific district
app.MapGet("/api/Statistics/JudicialDistrict/{districtId}/{year}", async (int districtId, int year, DataContext context) =>
{
    //Get all of the court names within the given district
    var courtByDistrict = await context.courts.Where(s => s.districtId.Equals(districtId)).ToArrayAsync();

    //Initilaize an empty Stats array.
    var statisticsByDistrict = await context.statistics.Where(s => s.courtID.Equals(999)).ToArrayAsync();
    var tempValue = statisticsByDistrict.Where(s => s.courtID.Equals(987654));//This will generate an empty Statistics array


    for (var i = 0; i < courtByDistrict.Length; i++)
    {
        //Get all of the courts stats from the courtByDistrict object.
        var tempcourtByID = await context.statistics.Where(s => s.courtID.Equals(courtByDistrict[i].id)).ToArrayAsync();
        var tempCourtByYear = tempcourtByID.Where(s => s.year.Equals(year));

        tempValue = tempValue.Concat(tempCourtByYear);
    }

    var newValue = tempValue.ToArray();

    Statistics allCourts = new Statistics();
    allCourts.juvFilings = 0;
    allCourts.famFilings = 0;
    allCourts.juvDispo = 0;
    allCourts.famDispo = 0;

    for (int i = 0; i < newValue.Length; i++)
    {
        allCourts.crimFilings += newValue[i].crimFilings;
        allCourts.civilFilings += newValue[i].civilFilings;
        allCourts.juvFilings += newValue[i].juvFilings;
        allCourts.famFilings += newValue[i].famFilings;
        allCourts.trafficFilings += newValue[i].trafficFilings;
        allCourts.crimDispo += newValue[i].crimDispo;
        allCourts.civilDispo += newValue[i].civilDispo;
        allCourts.juvDispo += newValue[i].juvDispo;
        allCourts.famDispo += newValue[i].famDispo;
        allCourts.trafficDispo += newValue[i].trafficDispo;
    }

    allCourts.year = year;

    return allCourts;
});


app.Run();