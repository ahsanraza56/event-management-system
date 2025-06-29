# Debug Instructions for Booking Issue

## 🎯 Current Issue

**Error**: "Number of selected seats must match the quantity."

## 🔍 Debug Steps

### Step 1: Test with Debug Route

I've temporarily changed the booking form to submit to `/debug/booking` instead of the actual booking route. This will help us see exactly what data is being sent.

### Step 2: Deploy and Test

1. **Push the changes**:
   ```bash
   git add .
   git commit -m "Add debug logging for booking issue"
   git push origin main
   ```

2. **Test the booking process**:
   - Go to an event with seat selection
   - Select 2-3 seats
   - Click "Test Booking (Debug)"
   - Check the response in the browser

### Step 3: Check the Response

The debug route will return JSON showing:
- All received data
- Quantity value
- Selected seats array
- Data types
- Counts

### Step 4: Check Railway Logs

After testing, check Railway logs for detailed information:
- Go to Railway dashboard
- Click on your deployment
- Check "View Logs"
- Look for "Booking request received" entries

## 🔧 What We've Added

### 1. **Debug Route** (`/debug/booking`)
- Returns all request data as JSON
- Shows data types and counts
- Helps identify what's being sent

### 2. **Enhanced Logging** (BookingController)
- Logs all request data
- Shows quantity and selected seats
- Tracks validation steps
- Records successful bookings

### 3. **Temporary Form Change**
- Form now submits to debug route
- Button text changed to "Test Booking (Debug)"
- Same JavaScript logic

## 📊 Expected Debug Output

When you test the booking, you should see:

```json
{
  "received_data": {
    "_token": "...",
    "quantity": "2",
    "selected_seats": ["1", "2"]
  },
  "quantity": "2",
  "selected_seats": ["1", "2"],
  "selected_seats_type": "array",
  "selected_seats_count": 2
}
```

## 🚨 Common Issues to Look For

1. **Quantity as string**: Should be integer
2. **Selected seats not array**: Should be array
3. **Empty selected seats**: Should have values
4. **Mismatched counts**: Quantity vs selected seats count

## 🔄 Next Steps

1. **Test the debug route** and share the response
2. **Check Railway logs** for detailed information
3. **Identify the exact issue** from the debug data
4. **Fix the specific problem** based on findings

## 📝 Files Modified

1. `routes/web.php` - Added debug route
2. `app/Http/Controllers/BookingController.php` - Added logging
3. `resources/views/events/seat-selection.blade.php` - Changed form action

## 🎯 After Debugging

Once we identify the issue:
1. Fix the specific problem
2. Change form action back to `{{ route('bookings.store', $event) }}`
3. Remove debug route
4. Test the actual booking process

Please test the debug route and share the response so we can identify the exact issue! 