import 'package:flutter/material.dart';
import 'package:mobile/shared/widgets/dosen/bottom_nav.dart';

class DosenDetailKelasPage extends StatefulWidget {
  const DosenDetailKelasPage({super.key});

  @override
  State<DosenDetailKelasPage> createState() => _DosenDetailKelasPageState();
}

class _DosenDetailKelasPageState extends State<DosenDetailKelasPage> {

  final _currentIndex = 1;

  void _onNavTapped(int index) {
    if (index == _currentIndex) return;

    switch (index) {
      case 0:
        Navigator.pushReplacementNamed(context, '/dosen/dashboard');
        break;
      case 1:
        Navigator.pushReplacementNamed(context, '/dosen/kelas');
        break;
      case 2:
        Navigator.pushReplacementNamed(context, '/profile');
        break;
    }
  }

  // 🔥 dummy meeting data (1–14)
  List<Map<String, dynamic>> meetings = List.generate(14, (i) {
    return {
      "number": i + 1,
      "date": DateTime(2026, 2, (i * 7) + 1),
      "isDone": i < 7,
      "attendance": i < 7 ? (80 + (i % 5) * 3) : null,
    };
  });

  int get completed => meetings.where((m) => m["isDone"]).length;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      bottomNavigationBar: BottomNav(
        currentIndex: _currentIndex,
        onTap: _onNavTapped,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [

            // 🔵 HEADER
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(20, 60, 20, 30),
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [Color(0xFF7C3AED), Color(0xFF6366F1)],
                ),
                borderRadius: BorderRadius.only(
                  bottomLeft: Radius.circular(30),
                  bottomRight: Radius.circular(30),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [

                  // BACK BUTTON
                  GestureDetector(
                    onTap: () {
                      if (Navigator.of(context).canPop()) {
                        Navigator.of(context).pop();
                      } else {
                        // Jika tidak bisa back (stack kosong), arahkan ke dashboard
                        Navigator.pushReplacementNamed(context, '/dosen/kelas');
                      }
                    },
                    child: Container(
                      width: 40,
                      height: 40,
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: 0.2),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: const Icon(Icons.arrow_back, color: Colors.white),
                    ),
                  ),

                  const SizedBox(height: 20),

                  const Text(
                    "Pemrograman Web",
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                    ),
                  ),

                  const Text(
                    "TI-301 • Genap 2025/2026",
                    style: TextStyle(color: Colors.white70),
                  ),

                  const SizedBox(height: 16),

                  // 📊 PROGRESS
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.15),
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Column(
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            const Text("Progress", style: TextStyle(color: Colors.white)),
                            Text(
                              "$completed/${meetings.length} Pertemuan",
                              style: const TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        LinearProgressIndicator(
                          value: completed / meetings.length,
                          backgroundColor: Colors.white24,
                          color: Colors.white,
                        )
                      ],
                    ),
                  )
                ],
              ),
            ),

            const SizedBox(height: 16),

            // 📊 STATS
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Row(
                children: [
                  Expanded(child: _statBox("32", "Mahasiswa", Colors.blue)),
                  const SizedBox(width: 10),
                  Expanded(child: _statBox("$completed", "Selesai", Colors.green)),
                  const SizedBox(width: 10),
                  Expanded(child: _statBox("${14 - completed}", "Tersisa", Colors.purple)),
                ],
              ),
            ),

            const SizedBox(height: 20),

            // 📋 TITLE
            const Padding(
              padding: EdgeInsets.symmetric(horizontal: 16),
              child: Align(
                alignment: Alignment.centerLeft,
                child: Text(
                  "Daftar Pertemuan",
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                ),
              ),
            ),

            const SizedBox(height: 10),

            // 📋 LIST MEETING
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                children: meetings.map((m) => _meetingCard(m)).toList(),
              ),
            ),

            const SizedBox(height: 80),
          ],
        ),
      ),
    );
  }

  // 🔹 STAT BOX
  Widget _statBox(String value, String label, Color color) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(16),
      ),
      child: Column(
        children: [
          Text(value, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
          Text(label, style: const TextStyle(fontSize: 12)),
        ],
      ),
    );
  }

  // 🔹 MEETING CARD
  Widget _meetingCard(Map<String, dynamic> m) {

    final isDone = m["isDone"];
    final date = m["date"] as DateTime;

    return Container(
      margin: const EdgeInsets.only(bottom: 14),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10)
        ],
      ),
      child: Row(
        children: [

          // ICON
          Container(
            width: 50,
            height: 50,
            decoration: BoxDecoration(
              color: isDone ? Colors.green.withValues(alpha: 0.1) : Colors.grey.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(14),
            ),
            child: Icon(
              isDone ? Icons.check_circle : Icons.access_time,
              color: isDone ? Colors.green : Colors.grey,
            ),
          ),

          const SizedBox(width: 12),

          // TEXT
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "Pertemuan ke-${m["number"]}",
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
                Text(
                  "${_formatDate(date)}",
                  style: const TextStyle(color: Colors.grey),
                ),
                if (isDone)
                  Text(
                    "Kehadiran: ${m["attendance"]}%",
                    style: const TextStyle(color: Colors.green),
                  )
              ],
            ),
          ),

          // ACTION
          if (isDone)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
              decoration: BoxDecoration(
                color: Colors.green.withValues(alpha: 0.2),
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Text("Selesai", style: TextStyle(color: Colors.green)),
            )
          else
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [Color(0xFF7C3AED), Color(0xFF6366F1)],
                ),
                borderRadius: BorderRadius.all(Radius.circular(12)),
              ),
              child: const Row(
                children: [
                  Icon(Icons.qr_code, color: Colors.white, size: 16),
                  SizedBox(width: 6),
                  Text("Mulai", style: TextStyle(color: Colors.white)),
                ],
              ),
            )
        ],
      ),
    );
  }

  // 🔹 FORMAT DATE
  String _formatDate(DateTime date) {
    const bulan = [
      "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
      "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
    ];

    const hari = [
      "Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"
    ];

    return "${hari[date.weekday % 7]}, ${date.day} ${bulan[date.month - 1]} ${date.year}";
  }
}